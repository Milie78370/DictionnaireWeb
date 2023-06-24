ReadMe
Le présent site est un dictionnaire multi-langues : 
* Le lien vers l’API : http://127.0.0.1:8000/api
* Le lien vers l’application Web : http://127.0.0.1:8001/
* Lancer dans un premier temps le serveur sur l’api ensuite sur l’application
* Deux types de connexions sont permises : une connexion administrateur et une connexion utilisateur : 
* En administrateur : 
   * Création d’un administrateur : 
Un administrateur peut être ajouté via une commande terminal comme suit : 
php bin/console app:add-user emiliejolie@gmail.com emiliejolie
Ainsi, nous avons un Admin emiliejolie@gmail.com avec un mot de passe emiliejolie.
Un autre administrateur créé : talulah@gmail.com avec un mot de passe Hello12!
NB : à noter que les mots de passe sont cryptés dans la base de données.
L’ajout d’un administrateur ne peut se faire que via la commande. Pour se connecter, l’administrateur devra renseigner ses identifiants sur la page de connexion.

   * Ajouter/Éditer des mots : 
  L’ajout des mots se fait via un formulaire où l’administrateur devra renseigner plusieurs champs à savoir le mot, sa catégorie (verbe, adverbe, adjectif,...etc.), sa langue et sa nature (féminin ou masculin). L’administrateur a la possibilité de visualiser et de modifier le mot ajouté.  

   * Ajouter des langues, catégories, traduction :
  Trois formulaires sont disponibles sur http://127.0.0.1:8001/word/new pour ces ajouts.  


* En utilisateur : 
  * Inscription/Connexion d’un utilisateur : 
  La connexion au site se fait via la page de connexion en renseignant son adresse email et son mot de passe. Si un utilisateur n’est pas encore inscrit, il faudra d’abord passer par l’étape inscription via un formulaire dédié avant de pouvoir se connecter. 
  Utilisateur créé : thomas@gmail.com avec pour mot de passe : thomas
  * Visualiser le dictionnaire :
  Un utilisateur visualise tous les mots et peut cliquer sur chaque mot pour en découvrir le détail et ses traductions existantes. 

  * Générer un mot random :
  Une fonctionnalité le permet. Il faut s’y reprendre à plusieurs reprises afin d’avoir un mot avec tous les champs renseignés (il se peut que l’on tombe sur des mots “tests”)
  * Télécharger le dictionnaire :
  Permet le téléchargement en JSON du dictionnaire.
  * Filtrer les mots du dictionnaire :
  Une recherche est possible par mot, catégorie et langue.