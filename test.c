#include <stdio.h>
#include <string.h>
#include <math.h>
#include <stdlib.h>

const long long max_size = 2000;
const long long N = 40;
const long long max_w = 50;

void extractWordsAndOffsets(const char *inputFileName, const char *outputFileName) {
    FILE *inputFile, *outputFile;

    inputFile = fopen(inputFileName, "rb");

    if (inputFile == NULL) {
        printf("Input file not found\n");
        return;
    }

    outputFile = fopen(outputFileName, "w");

    if (outputFile == NULL) {
        printf("Cannot create output file\n");
        fclose(inputFile);
        return;
    }

    long long words, size;
    char *vocab;
    float *M;

    // Lecture du nombre total de mots et de la dimension de l'espace vectoriel
    fscanf(inputFile, "%lld", &words);
    fscanf(inputFile, "%lld", &size);

    // Allocation de mémoire pour le vocabulaire et la matrice
    vocab = (char *)malloc((long long)words * max_w * sizeof(char));
    M = (float *)malloc((long long)words * (long long)size * sizeof(float));

    // Ignorer le reste de la ligne après la lecture de size
    while (fgetc(inputFile) != '\n');

    // Lecture du vocabulaire et des vecteurs depuis le fichier binaire
    for (long long b = 0; b < words; b++) {
        // Lecture du mot
        fscanf(inputFile, "%s%c", &vocab[b * max_w], &M[0]);

        // Lecture du vecteur et normalisation
        for (long long a = 0; a < size; a++) fread(&M[a + b * size], sizeof(float), 1, inputFile);

        // Écriture du mot et de son offset dans le fichier de sortie
        fprintf(outputFile, "%s %lld\n", &vocab[b * max_w], ftell(inputFile));
    }

    // Libération de la mémoire
    free(vocab);
    free(M);

    // Fermeture des fichiers
    fclose(inputFile);
    fclose(outputFile);
}

int main() {
    // Spécifier les noms des fichiers directement dans la fonction extractWordsAndOffsets
    extractWordsAndOffsets("words.bin", "words.txt");

    return 0;
}
