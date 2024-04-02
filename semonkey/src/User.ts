export class User {
    private idUser: number;
    private pseudo: string;
    private imageData: string | null;

    constructor(idUser: number, pseudo: string) {
        this.idUser = idUser;
        this.pseudo = pseudo;
        this.imageData = null;
    }

    getIdUser(): number {
        return this.idUser;
    }

    getPseudo(): string {
        return this.pseudo;
    }

    getImageData(): string | null {
        return this.imageData;
    }

    getImageSrc(): string {
        if (this.imageData !== null) {
            let base64 = btoa(this.imageData);
            return `data:image/jpeg;base64,${base64}`;
        } else {
            return "../img/profil.webp";
        }
    }
}