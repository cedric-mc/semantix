package com.but.semonkey;

import android.app.NotificationChannel;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.pm.PackageManager;
import android.os.Build;
import android.os.Bundle;
import android.util.Log;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.work.Constraints;
import androidx.work.OneTimeWorkRequest;
import androidx.core.app.ActivityCompat;
import androidx.core.app.NotificationCompat;
import androidx.core.app.NotificationManagerCompat;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import androidx.work.ExistingPeriodicWorkPolicy;
import androidx.work.NetworkType;
import androidx.work.PeriodicWorkRequest;
import androidx.work.WorkManager;

import com.google.android.material.bottomnavigation.BottomNavigationView;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.URLEncoder;
import java.util.ArrayList;
import java.util.List;
import java.util.concurrent.TimeUnit;

public class HomeActivity extends AppCompatActivity implements MessageAdapter.MessageClickListener {

    private RecyclerView recyclerView;
    private MessageAdapter messageAdapter;
    private List<Message> messageList;
    private BottomNavigationView bottomNavigationView;
    private SharedPreferences sharedPreferences;
    private Button refreshButton;
    private boolean variableBoolInitialized = false;
    private PeriodicWorkRequest periodicWork;
    private Button sendMessageButton;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_home);

        createNotificationChannel();

        if (!variableBoolInitialized) {
            // Initialiser la variable booléenne à false
            SharedPreferences.Editor editor = getSharedPreferences("MyPrefs", Context.MODE_PRIVATE).edit();
            editor.putBoolean("variable_bool", false);
            editor.apply();
            variableBoolInitialized = true; // Marquer la variable comme initialisée
        }

        // Planifiez le travail périodique
        Constraints constraints = new Constraints.Builder()
                .setRequiredNetworkType(NetworkType.CONNECTED)
                .build();

        periodicWork = new PeriodicWorkRequest.Builder(MyWorker.class, 15, TimeUnit.MINUTES)
                .setConstraints(constraints)
                .build();

        WorkManager.getInstance(getApplicationContext()).enqueueUniquePeriodicWork("MyWorker", ExistingPeriodicWorkPolicy.REPLACE, periodicWork);


        // Initialize SharedPreferences
        sharedPreferences = getSharedPreferences("AuthPrefs", Context.MODE_PRIVATE);

        // Initialize RecyclerView
        recyclerView = findViewById(R.id.recyclerViewMessages);
        recyclerView.setLayoutManager(new LinearLayoutManager(this));
        messageList = new ArrayList<>();
        messageAdapter = new MessageAdapter(messageList, this);
        recyclerView.setAdapter(messageAdapter);


        // Initialize BottomNavigationView
        bottomNavigationView = findViewById(R.id.bottomNavigation);
        bottomNavigationView.setSelectedItemId(R.id.action_messages);
        bottomNavigationView.setOnNavigationItemSelectedListener(new BottomNavigationView.OnNavigationItemSelectedListener() {
            @Override
            public boolean onNavigationItemSelected(@NonNull MenuItem item) {
                int itemId = item.getItemId();

                if (itemId == R.id.action_home) {
                    startActivity(new Intent(HomeActivity.this, WebViewActivity.class));
                    return true;
                } else if (itemId == R.id.action_logout) {
                    // Supprimer le token d'authentification des SharedPreferences
                    SharedPreferences.Editor editor = sharedPreferences.edit();
                    editor.remove("auth_token");
                    editor.apply();

                    // Rediriger vers LoginActivity
                    startActivity(new Intent(HomeActivity.this, LoginActivity.class));
                    finish(); // Optionnel : ferme l'activité actuelle pour empêcher de revenir en arrière
                    return true;
                }
                return false;
            }
        });

        // Retrieve auth token from SharedPreferences
        String authToken = sharedPreferences.getString("auth_token", null);
        if (authToken != null) {
            // Fetch messages from server using auth token
            messageList.clear();
            fetchMessages(authToken);
        } else {
            // Handle case where auth token is not available
            Toast.makeText(HomeActivity.this, "Auth token not found", Toast.LENGTH_SHORT).show();
        }

        // Définissez un écouteur pour surveiller les changements de la variable booléenne
        SharedPreferences.OnSharedPreferenceChangeListener listener = new SharedPreferences.OnSharedPreferenceChangeListener() {
            @Override
            public void onSharedPreferenceChanged(SharedPreferences sharedPreferences, String key) {
                if (key.equals("variable_bool")) {
                    boolean variableBool = sharedPreferences.getBoolean(key, false);
                    // Faites quelque chose avec la variable booléenne mise à jour
                    Log.d("VoirTest", "Variable booléenne : " + variableBool);
                    if (variableBool) {
                        Log.d("VoirTest", "Oui!");
                        messageList.clear();
                        fetchMessages(authToken);
                        // Remettez la variable booléenne à false après l'exécution de la fonction
                        SharedPreferences.Editor editor = sharedPreferences.edit();
                        editor.putBoolean("variable_bool", false);
                        editor.apply();
                    }
                }
            }
        };

        // Enregistrez l'écouteur dans SharedPreferences
        SharedPreferences prefs = getSharedPreferences("MyPrefs", Context.MODE_PRIVATE);
        prefs.registerOnSharedPreferenceChangeListener(listener);



        refreshButton = findViewById(R.id.refreshButton);
        refreshButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                fetchMessages(authToken);
            }
        });

        sendMessageButton = findViewById(R.id.sendMessageButton);
        sendMessageButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                // Ouvrir la SendMessageActivity lorsque le bouton est cliqué
                Intent intent = new Intent(HomeActivity.this, SendMessageActivity.class);
                startActivity(intent);
            }
        });


    }

    @Override
    public void onMessageClick(int position) {
        // Gérer le clic sur un message (ouvrir une activité pour afficher les détails du message)
        Message clickedMessage = messageList.get(position);
        Intent intent = new Intent(this, MessageDetailActivity.class);
        intent.putExtra("message", clickedMessage);
        startActivity(intent);
    }

    protected void fetchMessages(final String authToken) {
        new Thread(new Runnable() {
            @Override
            public void run() {
                try {
                    messageList.clear();
                    // Créer la connexion
                    URL url = new URL("https://perso-etudiant.u-pem.fr/~mariyaconsta02/semantix/android/recupe_message.php");
                    HttpURLConnection connection = (HttpURLConnection) url.openConnection();
                    connection.setRequestMethod("POST");
                    connection.setRequestProperty("Content-Type", "application/x-www-form-urlencoded");
                    connection.setRequestProperty("charset", "utf-8");
                    connection.setUseCaches(false);
                    connection.setDoOutput(true);

                    // Envoyer le jeton d'authentification au serveur
                    OutputStream outputStream = connection.getOutputStream();
                    String postData = "auth_token=" + URLEncoder.encode(authToken, "UTF-8");
                    outputStream.write(postData.getBytes());
                    outputStream.flush();
                    outputStream.close();

                    // Lire la réponse
                    BufferedReader reader = new BufferedReader(new InputStreamReader(connection.getInputStream()));
                    StringBuilder response = new StringBuilder();
                    String line;
                    while ((line = reader.readLine()) != null) {
                        response.append(line);
                    }
                    reader.close();

                    // Traiter la réponse JSON
                    JSONObject jsonResponse = new JSONObject(response.toString());
                    if (jsonResponse.getBoolean("success")) {
                        // Récupérer les messages existants
                        JSONArray jsonArray = jsonResponse.getJSONArray("messages");
                        for (int i = 0; i < jsonArray.length(); i++) {
                            JSONObject jsonMessage = jsonArray.getJSONObject(i);
                            String sender = jsonMessage.getString("expediteur");
                            String recipient = jsonMessage.getString("destinataire");
                            String subject = jsonMessage.getString("sujet");
                            String content = jsonMessage.getString("contenu");
                            String date = jsonMessage.getString("date");
                            // Créer un objet Message et l'ajouter à la liste
                            Message message = new Message(sender, recipient, subject, content, date);
                            messageList.add(message);
                        }

                        // Récupérer les nouveaux messages
                        JSONArray newMessagesArray = jsonResponse.getJSONArray("new_messages");
                        for (int i = 0; i < newMessagesArray.length(); i++) {
                            JSONObject jsonNewMessage = newMessagesArray.getJSONObject(i);
                            String sender = jsonNewMessage.getString("expediteur");
                            String recipient = jsonNewMessage.getString("destinataire");
                            String subject = jsonNewMessage.getString("sujet");
                            String content = jsonNewMessage.getString("contenu");
                            String date = jsonNewMessage.getString("date");
                            // Créer un objet Message pour les nouveaux messages
                            Message newMessage = new Message(sender, recipient, subject, content, date);
                            // Afficher une notification pour les nouveaux messages
                            showNotification(sender, subject, content);
                        }


                        // Notifier l'adaptateur du changement de données
                        runOnUiThread(new Runnable() {
                            @Override
                            public void run() {
                                messageAdapter.notifyDataSetChanged();
                            }
                        });
                    } else {
                        // Gérer le cas où aucun message n'a été récupéré
                        Log.d("HomeActivity", "Aucun message trouvé");
                    }
                } catch (IOException | JSONException e) {
                    e.printStackTrace();
                }
            }
        }).start();
    }

    // Méthode pour afficher une notification pour les nouveaux messages
    // Méthode pour afficher une notification pour les nouveaux messages
    private void showNotification(String sender, String subject, String content) {
        // Créer un intent pour ouvrir l'activité de détail du message lorsque la notification est cliquée
        Intent intent = new Intent(this, MessageDetailActivity.class);
        PendingIntent pendingIntent = PendingIntent.getActivity(this, 0, intent, PendingIntent.FLAG_IMMUTABLE);

        // Créer le contenu de la notification
        NotificationCompat.Builder builder = new NotificationCompat.Builder(this, "channel_id")
                .setSmallIcon(R.drawable.monkey)
                .setContentTitle(sender)
                .setContentText(subject)
                .setStyle(new NotificationCompat.BigTextStyle().bigText(content))
                .setContentIntent(pendingIntent)
                .setAutoCancel(true);

        // Afficher la notification
        NotificationManagerCompat notificationManager = NotificationManagerCompat.from(this);
        notificationManager.notify(1, builder.build());
    }

    // Créer un canal de notification
    private void createNotificationChannel() {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            CharSequence name = getString(R.string.app_name);
            String description = getString(R.string.message);
            int importance = NotificationManager.IMPORTANCE_DEFAULT;
            NotificationChannel channel = new NotificationChannel("channel_id", name, importance);
            channel.setDescription(description);

            // Enregistrer le canal avec le gestionnaire de notification
            NotificationManager notificationManager = getSystemService(NotificationManager.class);
            notificationManager.createNotificationChannel(channel);
        }
    }
}