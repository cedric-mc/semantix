package com.but.semonkey;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.URLEncoder;

public class SendMessageActivity extends AppCompatActivity {

    private EditText recipientEditText;
    private EditText subjectEditText;
    private EditText contentEditText;
    private Button sendButton;
    private SharedPreferences sharedPreferences;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_send_message);

        recipientEditText = findViewById(R.id.recipientEditText);
        subjectEditText = findViewById(R.id.subjectEditText);
        contentEditText = findViewById(R.id.contentEditText);
        sendButton = findViewById(R.id.sendButton);
        sharedPreferences = getSharedPreferences("AuthPrefs", MODE_PRIVATE);

        sendButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                sendMessage();
            }
        });

        // Récupérer les données pré-remplies depuis l'intent
        String recipient = getIntent().getStringExtra("recipient");
        String subject = getIntent().getStringExtra("subject");
        String content = getIntent().getStringExtra("content");

        // Trouver les champs destinataire, sujet et contenu dans l'interface utilisateur
        EditText recipientEditText = findViewById(R.id.recipientEditText);
        EditText subjectEditText = findViewById(R.id.subjectEditText);
        EditText contentEditText = findViewById(R.id.contentEditText);

        // Pré-remplir les champs avec les données récupérées
        if (recipient != null) {
            recipientEditText.setText(recipient);
        }
        if (subject != null) {
            subjectEditText.setText(subject);
        }
        if (content != null) {
            contentEditText.setText(content);
        }
    }

    private void sendMessage() {
        final String recipient = recipientEditText.getText().toString().trim();
        final String subject = subjectEditText.getText().toString().trim();
        final String content = contentEditText.getText().toString().trim();
        String authToken = sharedPreferences.getString("auth_token", null);

        // Vérifier que tous les champs sont remplis
        if (recipient.isEmpty() || subject.isEmpty() || content.isEmpty()) {
            Toast.makeText(this, "Veuillez remplir tous les champs", Toast.LENGTH_SHORT).show();
            return;
        }

        new Thread(new Runnable() {
            @Override
            public void run() {
                try {
                    // Création de la connexion HTTP
                    URL url = new URL("https://perso-etudiant.u-pem.fr/~mamadou.ba2/semantix/android/send_message.php");
                    HttpURLConnection connection = (HttpURLConnection) url.openConnection();
                    connection.setRequestMethod("POST");
                    connection.setRequestProperty("Content-Type", "application/x-www-form-urlencoded");
                    connection.setRequestProperty("charset", "utf-8");
                    connection.setUseCaches(false);
                    connection.setDoOutput(true);

                    // Construction des données à envoyer avec URLEncoder
                    String postDataString = "auth_token=" + URLEncoder.encode(authToken, "UTF-8") +
                            "&recipient=" + URLEncoder.encode(recipient, "UTF-8") +
                            "&subject=" + URLEncoder.encode(subject, "UTF-8") +
                            "&content=" + URLEncoder.encode(content, "UTF-8");

                    // Envoi des données
                    OutputStream outputStream = connection.getOutputStream();
                    outputStream.write(postDataString.getBytes());
                    outputStream.flush();
                    outputStream.close();

                    // Lecture de la réponse
                    BufferedReader reader = new BufferedReader(new InputStreamReader(connection.getInputStream()));
                    StringBuilder response = new StringBuilder();
                    String line;
                    while ((line = reader.readLine()) != null) {
                        response.append(line);
                    }
                    reader.close();

                    // Traitement de la réponse
                    final JSONObject responseJson = new JSONObject(response.toString());
                    runOnUiThread(new Runnable() {
                        @Override
                        public void run() {
                            try {
                                handleResponse(responseJson);
                            } catch (JSONException e) {
                                throw new RuntimeException(e);
                            }
                        }
                    });
                } catch (IOException | JSONException e) {
                    e.printStackTrace();
                }
            }
        }).start();
    }


    private void handleResponse(JSONObject response) throws JSONException {
        // Traitement de la réponse de votre serveur PHP
        if (response.getBoolean("success")) {
            Toast.makeText(this, "Message envoyé avec succès", Toast.LENGTH_SHORT).show();
            // Terminer l'activité actuelle et revenir à HomeActivity
            finish();
        } else {
            Toast.makeText(this, "Échec de l'envoi du message", Toast.LENGTH_SHORT).show();
        }
    }

}


