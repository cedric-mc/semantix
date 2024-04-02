package com.but.semonkey;

import java.io.Serializable;

public class Message implements Serializable {
    private String sender;
    private String recipient;
    private String subject;
    private String content;
    private String date;

    public Message(String sender, String recipient, String subject, String content, String date) {
        this.sender = sender;
        this.recipient = recipient;
        this.subject = subject;
        this.content = content;
        this.date = date;
    }

    // Getter et setter pour l'expéditeur
    public String getSender() {
        return sender;
    }

    public void setSender(String sender) {
        this.sender = sender;
    }

    // Getter et setter pour le destinataire
    public String getRecipient() {
        return recipient;
    }

    public void setRecipient(String recipient) {
        this.recipient = recipient;
    }

    // Getter et setter pour le sujet
    public String getSubject() {
        return subject;
    }

    public void setSubject(String subject) {
        this.subject = subject;
    }

    // Getter et setter pour le contenu
    public String getContent() {
        return content;
    }

    public void setContent(String content) {
        this.content = content;
    }

    // Getter et setter pour la date
    public String getDate() {
        return date;
    }

    public void setDate(String date) {
        this.date = date;
    }
}
