import ChatManager from './ChatManager';

class ChatManagerImpl implements ChatManager {
    private messageReceiver: ((content: string) => void) | null = null;

    // Ouvre la connexion (simulé)
    async open(): Promise<void> {
        // Cette méthode ne fait rien de spécifique pour simuler une connexion immédiate.
        return Promise.resolve();
    }

    // Définit le récepteur de message
    setMessageReceiver(receiver: (content: string) => void): void {
        this.messageReceiver = receiver;
    }

    // Envoie un message transformé (simulé)
    sendMessage(content: string): void {
        if (this.messageReceiver) {
            const transformedMessage = content.toUpperCase(); // Par exemple, transformation en majuscules
            this.messageReceiver(transformedMessage);
        }
    }

    // Ferme la connexion (simulé)
    close(): void {
        // Cette méthode ne fait rien de spécifique pour simuler la fermeture de la connexion.
    }
}

export default ChatManagerImpl;