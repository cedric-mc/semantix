interface ChatManager {
    open(): Promise<void>;
    setMessageReceiver(receiver: (content: string) => void): void;
    sendMessage(content: string): void;
    close(): void;
}

export default ChatManager;