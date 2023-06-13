# Projet-p2
Logiciel de gestion des feuilles d’émargement

Matthieu Boubée de Gramont - Lucas Dallas Costa

# INTRODUCTION

# 1 - Contexte et définition du projet

Un établissement scolaire gère la présence des élèves à travers des feuilles d'émargement. Les élèves doivent signer une feuille pour chaque cours et les enseignants la signent également en validant ou non la présence de chaque élève. 
A travers une application WEB nous allons chercher à automatiser tout le processus de signature des feuilles d’émargement.
Cet établissement scolaire gère des dizaines de classes (BTS SN1, BTS SIO 2, RPI, ESI 1, ...).
Pour certains cours une classe peut être divisée en plusieurs groupes (SLAM, DSN, CIR, ...).


# 2 - Objectif du projet

A travers l'application il sera possible: 

- Permettre à l’administration de visualiser et d’imprimer au format PDF toutes les feuilles
d’émargement
- La signature sera faite (par les enseignants ou les élèves) en saisissant leur nom et un code de
6 chiffres confidentiel
- Afin d’éviter qu’un élève signe présent à distance le système doit permettre à l’enseignant de
confirmer la présence de chaque élève
- Permettre la création de classes et de groupes
- Permettre la création d’enseignants
- Permettre la création d’élèves et la possibilité de les lier à une classe et à des groupes

# 3 - Les contraintes

Temporel: du 13 juin 2022 au 31 août 2022.

# 4 - Les fonctionnaltés


Un élève suite à une connexion peut:
- voir ses prochains cours 
- signer les feuilles liées au cours auquel il participe

Un enseignant suite à une connexion peut:
- voir ses prochains cours 
- signer les feuilles liées au cours auquel il participe
- valider la présence des élèves dans ses cours 
- visualiser les feuilles d'émargements
- imprimer les feuilles d'émargements
- créer un nouveau cours

Un administrateur suite à une connexion peut:
- visualiser les feuilles d'émargements
- imprimer les feuilles d'émargements
- créer un nouveau cours
- créer un nouvel élève
- créer un nouveau prof
- créer un nouveau groupe 


# 5 - Les livrables 

## MCD

Voici le mcd de notre bdd.

![image](https://user-images.githubusercontent.com/59642769/178200417-1338b823-4f12-4ea3-a148-2f200b2ae5cb.png)


## MLD

Voici le mld de notre bdd:

![image](https://user-images.githubusercontent.com/59642769/178739010-9665dce4-d1c2-4e3f-bae3-9d790a337348.png)


## DICTIONNAIRE DES ATTRIBUTS

Voici le dictionnaire des attributs de notre bbd: 

![image](https://user-images.githubusercontent.com/59642769/177116146-bb025498-7a04-420e-82f4-07bf31f9381a.png)

## DIAGRAMME D'ACTIVITÉ 

![image](https://user-images.githubusercontent.com/59642769/178714765-67157b5f-0429-4120-a88c-ca04cb19070a.png)


## DIAGRAMME DE CAS D'UTILISATION 

![image](https://user-images.githubusercontent.com/59642769/177103774-5c3c4ccd-c4f0-409a-b8f3-d12e04a73ba7.png)
#   p r o j e c t _ d e s e c f  
 