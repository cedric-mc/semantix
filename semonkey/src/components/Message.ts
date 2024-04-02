export interface Message {
    kind: 'received' | 'sent'
    content: string
    date: Date
}
