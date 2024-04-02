package com.but.semonkey;

import android.annotation.SuppressLint;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.SharedPreferences;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.Bundle;
import android.view.MenuItem;
import android.view.View;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;

import com.google.android.material.bottomnavigation.BottomNavigationView;

public class WebViewActivity extends AppCompatActivity {
    private WebView wv;
    private ProgressBar progressBar;
    private TextView textView;
    private ImageView imageView;
    private RelativeLayout loadingLayout;
    private ConnectivityChangeReceiver connectivityChangeReceiver;
    private BottomNavigationView bottomNavigationView;
    private SharedPreferences sharedPreferences;

    @SuppressLint("MissingInflatedId")
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_webview);

        wv = findViewById(R.id.webview);
        progressBar = findViewById(R.id.progressBar);
        imageView = findViewById(R.id.imageView);
        textView = findViewById(R.id.messageTextView);
        loadingLayout = findViewById(R.id.loadingLayout);
        bottomNavigationView = findViewById(R.id.bottomNavigation);

        sharedPreferences = getSharedPreferences("AuthPrefs", Context.MODE_PRIVATE);

        WebSettings webSettings = wv.getSettings();
        webSettings.setJavaScriptEnabled(true);

        // Vérifier si le périphérique est connecté à Internet
        if (isNetworkAvailable()) {
            // Charger l'URL dans WebView
            loadUrl();
        } else {
            // Afficher un message d'erreur si le périphérique n'est pas connecté à Internet
            Toast.makeText(this, "Veuillez vous connecter à Internet", Toast.LENGTH_SHORT).show();
            // Cacher le WebView et afficher le layout de chargement
            wv.setVisibility(View.GONE);
            loadingLayout.setVisibility(View.VISIBLE);
        }

        // Enregistrer le BroadcastReceiver pour détecter les changements de connectivité
        connectivityChangeReceiver = new ConnectivityChangeReceiver();
        IntentFilter filter = new IntentFilter(ConnectivityManager.CONNECTIVITY_ACTION);
        registerReceiver(connectivityChangeReceiver, filter);

        // Gérer la sélection des éléments de la Bottom Navigation View
        bottomNavigationView.setOnNavigationItemSelectedListener(new BottomNavigationView.OnNavigationItemSelectedListener() {
            @Override
            public boolean onNavigationItemSelected(@NonNull MenuItem item) {
                int itemId = item.getItemId();
                if (itemId == R.id.action_messages) {
                    // Redirection vers une autre activité avec un WebView
                    startActivity(new Intent(WebViewActivity.this, HomeActivity.class));
                    return true;
                } else if (itemId == R.id.action_home) {
                    // Rester sur cette activité (déjà dans la vue des messages)
                    return true;
                }else if (itemId == R.id.action_logout) {
                    // Supprimer le token d'authentification des SharedPreferences
                    SharedPreferences.Editor editor = sharedPreferences.edit();
                    editor.remove("auth_token");
                    editor.apply();

                    // Rediriger vers LoginActivity
                    startActivity(new Intent(WebViewActivity.this, LoginActivity.class));
                    finish(); // Optionnel : ferme l'activité actuelle pour empêcher de revenir en arrière
                    return true;
                }
                // Ajoutez d'autres cas ici si nécessaire
                return false;
            }

        });
    }

    @Override
    protected void onDestroy() {
        super.onDestroy();
        // Désenregistrer le BroadcastReceiver lors de la destruction de l'activité
        unregisterReceiver(connectivityChangeReceiver);
    }

    // Méthode pour charger l'URL dans le WebView
    private void loadUrl() {
        wv.loadUrl("https://perso-etudiant.u-pem.fr/~mariyaconsta02/semantix/");
        wv.setWebViewClient(new WebViewClient() {
            @Override
            public void onPageFinished(WebView view, String url) {
                super.onPageFinished(view, url);
                // Masquer la vue de chargement lorsque le chargement est terminé
                progressBar.setVisibility(View.GONE);
            }
        });
    }

    // BroadcastReceiver pour détecter les changements de connectivité
    private class ConnectivityChangeReceiver extends BroadcastReceiver {
        @Override
        public void onReceive(Context context, Intent intent) {
            if (isNetworkAvailable()) {
                // Charger l'URL dans le WebView dès qu'une connexion est détectée
                wv.setVisibility(View.VISIBLE);
                loadingLayout.setVisibility(View.GONE);
                loadUrl();
            } else {
                // Cacher le WebView et afficher le layout de chargement si la connexion est perdue
                wv.setVisibility(View.GONE);
                loadingLayout.setVisibility(View.VISIBLE);
                progressBar.setVisibility(View.VISIBLE);
                imageView.setVisibility(View.VISIBLE);
                textView.setVisibility(View.VISIBLE);
            }
        }
    }

    // Méthode pour vérifier si le périphérique est connecté à Internet
    private boolean isNetworkAvailable() {
        ConnectivityManager cm = (ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);
        NetworkInfo networkInfo = cm.getActiveNetworkInfo();
        return networkInfo != null && networkInfo.isConnected();
    }
}
