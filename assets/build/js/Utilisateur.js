class Utilisateur {

    #id;
    #cin;
    #nom;
    #prenom;
    #age;
    #adresse;

    constructor(id, cin, nom, prenom, age, adresse) {
        this.id = id;
        this.cin = cin;
        this.nom = nom;
        this.prenom = prenom;
        this.age = age;
        this.adresse = adresse;
    }

    get getId() {
        return this.id;
    }
    set setId(id) {
        this.id = id;
    }

    get getCin() {
        return this.cin;
    }
    set setCin(cin) {
        this.cin = cin;
    }

    get getNom() {
        return this.nom;
    }
    set setNom(nom) {
        this.nom = nom;
    }

    get getPrenom() {
        return this.prenom;
    }
    set setPrenom(prenom) {
        this.prenom = prenom;
    }

    get getAge() {
        return this.age;
    }
    set setAge(age) {
        this.age = age;
    }

    get getAdresse() {
        return this.adresse;
    }
    set setAdresse(adresse) {
        this.adresse = adresse;
    }

}