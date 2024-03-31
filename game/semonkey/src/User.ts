export class User {
    private idUser: number;
    private pseudo: string;
    private email: string;
    private year: number;
    private imageData: string | null;

    constructor(idUser: number, pseudo: string, email: string, year: number, imageData: string | null = null) {
        this.idUser = idUser;
        this.pseudo = pseudo;
        this.email = email;
        this.year = year;
        this.imageData = imageData;
    }

    static createUserFromUser(user: User) {
        return new User(user.getIdUser(), user.getPseudo(), user.getEmail(), user.getYear(), user.getImageData());
    }

    getIdUser(): number {
        return this.idUser;
    }

    getPseudo(): string {
        return this.pseudo;
    }

    getEmail(): string {
        return this.email;
    }

    getYear(): number {
        return this.year;
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

    setEmail(newEmail: string): void {
        this.email = newEmail;
    }

    setImageData(imageData: string | null): void {
        this.imageData = imageData;
    }

    // Note: The methods `isEmailExist`, `modifyEmail`, and `logging` are not included in this TypeScript class
    // because they interact with a database, which is typically handled outside of the class in JavaScript and TypeScript.
}