package com.but.semonkey;

import android.content.Context;
import android.content.Intent;
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
import java.io.DataOutputStream;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.URLEncoder;


public class LoginActivity extends AppCompatActivity {
    private EditText usernameEditText, passwordEditText;
    private Button loginButton;
    private Button recupeButton;
    private Button createAccountButton;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        usernameEditText = findViewById(R.id.usernameEditText);
        passwordEditText = findViewById(R.id.passwordEditText);
        loginButton = findViewById(R.id.loginButton);
        recupeButton = findViewById(R.id.retrieveSessionButton);
        createAccountButton = findViewById(R.id.createAccountButton);


        loginButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                String username = usernameEditText.getText().toString();
                String password = passwordEditText.getText().toString();
                authenticateUser(username, password);
            }
        });

        recupeButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                recupeUser();
            }
        });

        createAccountButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                // Ouvrir MainActivity
                Intent intent = new Intent(LoginActivity.this, CreateActivity.class);
                startActivity(intent);
            }
        });
    }

    private void recupeUser() {
        // Créer une nouvelle tâche asynchrone pour effectuer l'authentification
        new Thread(new Runnable() {
            @Override
            public void run() {
                // Vérifier d'abord si le jeton est déjà enregistré dans SharedPreferences
                SharedPreferences sharedPreferences2 = getSharedPreferences("AuthPrefs", Context.MODE_PRIVATE);
                String authToken2 = sharedPreferences2.getString("auth_token", null);
                Log.d("TAG", "Contenu de token : " + authToken2);

                if (authToken2 != null) {
                    // Vérifier si le jeton est présent dans la base de données et n'est pas expiré
                    // Vous devez implémenter cette vérification du côté du serveur
                    if (isValidToken(authToken2)) {
                        // Rediriger directement vers HomeActivity
                        Intent intent = new Intent(LoginActivity.this, HomeActivity.class);
                        startActivity(intent);
                        finish();
                        return; // Arrêter l'exécution de la méthode si le jeton est valide
                    }
                }
                runOnUiThread(new Runnable() {
                    @Override
                    public void run() {
                        Toast.makeText(LoginActivity.this, "Aucune session active, veuillez vous connecter", Toast.LENGTH_SHORT).show();
                    }
                });
            }
        }).start();
    }


    private void authenticateUser(final String username, final String password) {
        // Créer une nouvelle tâche asynchrone pour effectuer l'authentification
        new Thread(new Runnable() {
            @Override
            public void run() {
                try {
                    // Créer une connexion HTTP
                    URL url = new URL("https://perso-etudiant.u-pem.fr/~mamadou.ba2/semantix/connexion/script-connexion-mobile.php");
                    HttpURLConnection connection = (HttpURLConnection) url.openConnection();

                    // Paramètres de la requête
                    connection.setRequestMethod("POST");
                    connection.setDoOutput(true);

                    // Construire les données à envoyer
                    String postData = "pseudo=" + URLEncoder.encode(username, "UTF-8") +
                            "&motdepasse=" + URLEncoder.encode(password, "UTF-8");

                    // Envoyer les données d'authentification
                    DataOutputStream outputStream = new DataOutputStream(connection.getOutputStream());
                    outputStream.writeBytes(postData);
                    outputStream.flush();
                    outputStream.close();

                    // Lire la réponse du serveur
                    BufferedReader reader = new BufferedReader(new InputStreamReader(connection.getInputStream()));
                    StringBuilder response = new StringBuilder();
                    String line;
                    while ((line = reader.readLine()) != null) {
                        response.append(line);
                    }
                    reader.close();
                    // Analyser la réponse JSON
                    Log.d("TAG", "Contenu de maVariable : " + response);
                    JSONObject jsonResponse = new JSONObject(response.toString());
                    boolean success = jsonResponse.getBoolean("success");
                    if (success) {
                        String authToken = jsonResponse.getString("auth_token");
                        // Enregistrer le jeton d'authentification dans SharedPreferences
                        SharedPreferences sharedPreferences = getSharedPreferences("AuthPrefs", Context.MODE_PRIVATE);
                        SharedPreferences.Editor editor = sharedPreferences.edit();
                        editor.putString("auth_token", authToken);
                        editor.apply();

                        // Rediriger vers l'activité suivante après l'authentification réussie
                        Intent intent = new Intent(LoginActivity.this, HomeActivity.class);
                        startActivity(intent);
                        finish();
                    } else {
                        // Afficher un message d'erreur si l'authentification échoue
                        runOnUiThread(new Runnable() {
                            @Override
                            public void run() {
                                Toast.makeText(LoginActivity.this, "Identifiants invalides", Toast.LENGTH_SHORT).show();
                            }
                        });
                    }
                } catch (IOException | JSONException e) {
                    e.printStackTrace();
                }
            }
        }).start();
    }
    private boolean isValidToken(String authToken) {
        try {
            // Créer une connexion HTTP
            URL url = new URL("https://perso-etudiant.u-pem.fr/~mamadou.ba2/semantix/connexion/verif-token-mobile.php");
            HttpURLConnection connection = (HttpURLConnection) url.openConnection();

            // Paramètres de la requête
            connection.setRequestMethod("POST");
            connection.setDoOutput(true);

            // Construire les données à envoyer
            String postData = "auth_token=" + URLEncoder.encode(authToken, "UTF-8");

            // Envoyer les données d'authentification
            DataOutputStream outputStream = new DataOutputStream(connection.getOutputStream());
            outputStream.writeBytes(postData);
            outputStream.flush();
            outputStream.close();

            // Lire la réponse du serveur
            BufferedReader reader = new BufferedReader(new InputStreamReader(connection.getInputStream()));
            StringBuilder response = new StringBuilder();
            String line;
            while ((line = reader.readLine()) != null) {
                response.append(line);
            }
            reader.close();

            // Analyser la réponse JSON
            JSONObject jsonResponse = new JSONObject(response.toString());
            boolean success = jsonResponse.getBoolean("success");
            return success;
        } catch (IOException | JSONException e) {
            e.printStackTrace();
        }
        return false;
    }

}
