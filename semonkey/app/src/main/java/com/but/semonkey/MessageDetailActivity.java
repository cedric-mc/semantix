package com.but.semonkey;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;

public class MessageDetailActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_message_detail);

        // Récupérer le message depuis l'intent
        Message message = (Message) getIntent().getSerializableExtra("message");

        // Afficher les détails du message dans l'interface utilisateur
        TextView senderTextView = findViewById(R.id.senderTextView);
        TextView recipientTextView = findViewById(R.id.recipientTextView);
        TextView subjectTextView = findViewById(R.id.subjectTextView);
        TextView contentTextView = findViewById(R.id.contentTextView);
        TextView dateTextView = findViewById(R.id.dateTextView);
        Button replyButton = findViewById(R.id.replyButton);

        senderTextView.setText("Expéditeur: " + message.getSender());
        recipientTextView.setText("Destinataire: " + message.getRecipient());
        subjectTextView.setText("Sujet: " + message.getSubject());
        contentTextView.setText(message.getContent());
        dateTextView.setText("Date: " + message.getDate());

        // Ajouter un OnClickListener au bouton "Répondre"
        replyButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                // Ouvrir SendMessageActivity et pré-remplir les champs destinataire et sujet
                Intent replyIntent = new Intent(MessageDetailActivity.this, SendMessageActivity.class);
                replyIntent.putExtra("recipient", message.getSender()); // Expéditeur devient destinataire
                replyIntent.putExtra("subject", "Re: " + message.getSubject()); // Ajouter "Re:" au sujet
                replyIntent.putExtra("content", '"' + message.getContent()+ '"');
                startActivity(replyIntent);
            }
        });
    }
}