package com.but.semonkey;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import java.util.List;

public class MessageAdapter extends RecyclerView.Adapter<MessageAdapter.MessageViewHolder> {

    private List<Message> messageList;
    private MessageClickListener clickListener;

    public interface MessageClickListener {
        void onMessageClick(int position);
    }

    public MessageAdapter(List<Message> messageList, MessageClickListener clickListener) {
        this.messageList = messageList;
        this.clickListener = clickListener;
    }

    @NonNull
    @Override
    public MessageViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_message, parent, false);
        return new MessageViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull MessageViewHolder holder, int position) {
        Message message = messageList.get(position);
        holder.senderTextView.setText(message.getSender());
        holder.subjectTextView.setText(message.getSubject());
        holder.dateTextView.setText(message.getDate());
    }

    @Override
    public int getItemCount() {
        return messageList.size();
    }

    class MessageViewHolder extends RecyclerView.ViewHolder implements View.OnClickListener {
        TextView senderTextView;
        TextView subjectTextView;
        TextView dateTextView;

        public MessageViewHolder(@NonNull View itemView) {
            super(itemView);
            senderTextView = itemView.findViewById(R.id.senderTextView);
            subjectTextView = itemView.findViewById(R.id.subjectTextView);
            dateTextView = itemView.findViewById(R.id.dateTextView);
            itemView.setOnClickListener(this);
        }

        @Override
        public void onClick(View v) {
            int position = getAdapterPosition();
            if (position != RecyclerView.NO_POSITION) {
                clickListener.onMessageClick(position);
            }
        }
    }
}

