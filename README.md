# Progetto "Area Informatica": Esame di "Programmazione per il web"

## Italiano

Questo repository, raccoglie tutto il materiale prodotto per il superamento — da parte del sottoscritto — dell'esame di "Programmazione per il web".\
Ho svolto tale esame nel 2015, durante i miei studi al dipartimento di informatica dell'Università degli studi di Bari "Aldo Moro".

In tale esame era previsto lo sviluppo di un caso di studio: un sito di e-learning, che ho sviluppato con due colleghi.

L'obiettivo era certificare l'apprendimento dei linguaggi PHP, Javascript, CSS ed HTML.\
Qui vien pubblicata solo **la mia parte** del sito, ovvero:

- Alcuni documenti di progetto e documentazione generale;
- Il codice backend;
- Un layout spartano;


Furono gli altri due colleghi ad occuparsi del layout, dello stile e del codice client-side.


**DISCLAIMER**: Non usare questo progetto per scopi diversi da quelli d'esercizio/educativi. Se ne sconsiglia l'uso in produzione, in ogni caso, non mi assumo responsabilità.\
Ricordo inoltre che questo progetto risale al 2015, pertanto, non rappresenta più quelle che sono le mie competenze, il mio stile di codifica e la mia esperienza.


### Copyright

Questo software è distribuito sotto la licenza GPLv3 perchè fa uso di CKEditor v4.5.1, che può essere ridistribuito sotto licenze GPLv2+.


### Note

- Ho avuto un po' di difficoltà ad impostare il progetto Docker. Nel lontano 2015 non usavo nè Docker nè i VCS, ergo, le diverse versioni di questo caso di studio erano memorizzate in diversi file zip.\
Ho dovuto partire da una precedente "versione base", per via della necessità di rimuovere il lavoro degli altri colleghi; Morale della storia: ho dovuto confrontare i vari zip ed eseguire dei backport, in quanto errori precedenti erano stati risolti in seguito, ma senza indicazioni esplicite. Come se non bastasse, c'erano alcuni bug critici che ai tempi mi son sfuggiti e che dovevo risolvere;

- L'editor CKEditor è stato integrato per eseguire l'edit dei post del sito. La versione usata è oggi considerata software legacy.\
Una copia di tale editor è fornita con questo progetto in formato zip, ma è ancora possibile scaricarla da [https://ckeditor.com/cke4/release/CKEditor-4.5.1](https://ckeditor.com/cke4/release/CKEditor-4.5.1);


### Utilizzare il progetto Docker

Digitate i seguenti comandi:

```
git clone https://github.com/ad-gialluisi/uni-webdev
cd uni-webdev/docker-areainformatica
make
```

Il Makefile supporta i seguenti target:

- *stop*: ferma il container attualmente in esecuzione;
- *rm*: rimuove l'immagine Docker;
- *build*: costruisce l'immagine Docker;
- *run*: esegue un container della suddetta immagine;
- *all*: esegue tutti i target prima citati nell'ordine: *stop*, *rm*, *build* e *run*;


Il progetto docker imposta dei contenuti campione; Ci sono due utenti disponibili:

- `adgialluisi` con password `adgialluisi`;
- `jules` con password `julesjules`;




## English

This repository gathers all the materials I produced in order to pass the "web programming exam".\
I've taken the exam in 2015, during my studies at the Computer Science department of the University of Bari "Aldo Moro".

The exam provided for the development of a case study: an e-learning website, developed with two other colleagues of mine.

It served the purpose of practicing and studying the PHP, Javascript, CSS and HTML languages.\
Here is published only **my part** of the work, that is:

- Some project documents and generic documentation;
- The backend code;
- A bare-bones layout;

The other two colleagues dealt with the layout, the style and the client-side code.


**DISCLAIMER**: Do not use this project for anything other than educational purposes. I advise against using it in production environments, in any case, I won't take any responsability if you do.\
I also remind you that this project dates back to 2015, therefore, this doesn't represent my skills, my coding style and my experience anymore.

### Copyright

This software is distributed under the GPLv3 license because it uses CKEditor v4.5.1, which may be redistribuited under GPLv2+ license.


### Notes

- I had a some difficulties while creating the Docker proejct. Back in 2015 I didn't use neither Docker and VCSs, therefore, the different versions of this case study were stored in zip files.\
I had to start from a previous "base version", due to the need of removing the other colleagues' work; Moral of the story: I had to compare the different zip files and perform some backports, as previous errors were corrected in following versions without any proper indication. As if it wasn't enough, there were some critical bugs that I completely missed back in the day, which I had to solve;

- The CKEditor editor has been integrated in order to perform the editing of the posts of the website. The used version is considered legacy software now.\
A copy of it is distributed with this project as a zip file, but it is still possible to download it from [https://ckeditor.com/cke4/release/CKEditor-4.5.1](https://ckeditor.com/cke4/release/CKEditor-4.5.1);

- For the international audience: While the code is written in English, the comments on the source files, the project documents and the generic documentation are written in Italian.\
Sorry about that;


### Using the Docker project

Just type the following commands:

```
git clone https://github.com/ad-gialluisi/uni-webdev
cd uni-webdev/docker-areainformatica
make
```

The Makefile supports the following targets:

- *stop*: it stops the currently running Docker container;
- *rm*: it removes the Docker image;
- *build*: it builds the Docker image;
- *run*: it runs a container of the above-said image;
- *all*: runs all the targets listed earlier in the following order: *stop*, *rm*, *build*, *run*;


The docker project sets some sample contents; There are two available users:

- `adgialluisi` with password `adgialluisi`;
- `jules` with password `julesjules`;
