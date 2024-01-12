Pour compiler le jeu : 
gcc -o new_game new_game.c fonctions.c -lm
gcc -o add_word add_word.c fonctions.c -lm

Pour lancer nouvelle partie : 
télécharger le modèle : frWac_non_lem_no_postag_no_phrase_200_cbow_cut100.bin (120mb) et le renommer words.bin et le mettre dans le dosssier des fichiers c
./new_game words.bin arbre.lex mot1 mot2 mot3 .....
(ajouter au moins 1 mot)
Un fichier : fichier_de_partie.txt va être créée

Pour ajouter un mot au fichier de partie :
./add_word words.bin arbre.lex mot
Le fichier fichier_de_partie.txt est mis à jour


