# Blogram

---

## Descrizione:


Blogram è un applicativo php per la costruzione di un sistema multiblog.
Sviluppato per la realizzazione del progetto di Basi di Dati.

### MVC Pattern

E' stato utilizzato il pattern architetturale ***MVC*** (Model View Controller). La struttura del progetto è suddivisa in tre macro-categorie di componenti, che connesse costituiscono l’applicazione: i model si occupano di interrogare il database con apposite query, e vengono mostrati tramite le view all'utente; le view, sono pagine web che contengono l’interfaccia grafica e presentano gli output; e i controller, ricevono gli input direttamente dall'utente e li rendono comandi che vanno a modificare lo stato degli altri due componenti.

### PDO

Come strumento di connessione al database è stato scelto ***PDO*** (PHP Data Object). I vantaggi di utilizzare PDO, fanno riferimento alle seguenti caratteristiche: 
- Innanzi tutto in termini di **Portabilità**: visto che la sua concezione è di fornire un’unica interfaccia di programmazione con cui interagire con tutte le basi di dati;


- **Sicurezza**: grazie ai prepared statement con cui è gestita ogni query, utilizzando un'istruzione preparata, l'applicazione evita di ripetere il ciclo di analisi/compilazione/ottimizzazione. Ciò significa che le istruzioni preparate utilizzano meno risorse e quindi vengono eseguite più velocemente;  inoltre i prepared statements agiscono a difesa degli attacchi di SQL Injection, in quanto l’utente non può farsi strada attraverso il codice della query, essendo le query pre-compilate.

### Routing

Per il routing dell'applicativo è stata costruita una classe di routing elementare, che recupera il path della url chiamata, e tramite una regex accoda al path la stringa Controller e chiama la funzione main della classe identificata come ```{path}Controller.php``` passando come parametri della funzione tutte le variabili presenti in query string. Per fare un esempio: chiamando ```/blog``` verrà chiamata la funzione ```main``` della classe ```BlogController.php```, con l'eccezione di ```/``` che chiama direttamente ```HomeController.php```.

### Template engine

Per poter gestire le view come template, è stato utilizzato un Template Engine in modo da tenere separato il codice dai template visuali. 

Maggiori informazioni sul template engine utilizzato possono essere trovate <a href="https://codeshack.io/lightweight-template-engine-php/" class="external-link" target="_blank">qui</a>.

Questa classe permette di renderizzare un template senza la necessità di fare require all'interno del codice dei controller, infatti "compila" il codice del template sostituendo alla sintassi di template engine il corrispettivo codice php. Questo ci permette di utilizzare tutte le sintassi classiche dei template engine come:

```console
{% extends base.html %}
```
utile per annidare più template uno dentro l'altro;

```console
{% yield title %}
...
{% block title %}Home Page{% endblock %}
```
per poter sovrascrivere blocchi di template;

```console
{% foreach($colors as $color): %}
...
{% endforeach %}
```
o altre sintassi php per poter lavorare gli oggetti nei template;

Un esempio di applicazione può essere il seguente: 

```console
TemplateCore::view('homepage.html', array("blogs" => $result, "n_blogs" => $n_blogs));
```

Specificando il template da renderizzare e l'array dei valori da mandare ai template, tali oggetti possono poi essere ciclati come: 
 
```console 
{% foreach($blogs as $blog): %}
...
{% endforeach %}
```

### Autoload

Con l'obbiettivo di ottimizzare la leggibilità dei file, è stato utilizzato un sistema di autoload delle classi, grazie alla funzione ```autoloadFunction()``` che troviamo in index.php, registrata con spl_autoload_register che in automatico aggiunge il require della classe istanziata, risparmiandoci una lunga lista di 'include' all'inizio di ogni file.

---

## Requirements:

- PHP 7.4+
- Mysql 5.7+


---

## Installazione:

### Database
Per poter eseguire l'applicativo è necessario caricare il dump del database presente nella cartella ```Example/``` del progetto (```blog_db.sql```).
Il file ```blog_db``` contiene già una serie di dati precaricati compresi alcuni blog, post e commenti, per poter effettuare un accesso al blog in modalità autore.

Il dump deve essere caricato all'interno di un database dal nome ```blog_db``` con user e password ```blog_db``` localizzato su ```127.0.0.1:3306```.
Se fosse necessario modificare uno di questi parametri è possibile modificare il file ```/App/Utils/Config.php``` adeguandolo ai nuovi valori.
All'interno di tale file è presente anche la variabile ```SHOW_ERRORS``` (di default true) che permette di stampare gli errori a schermo (true) o di visualizzare una error page di produzione (false).

### Webservice

Una volta caricato il database è possibile eseguire l'applicativo attraverso un webserver.
E' possibile utilizzare un webserver come Apache o Nginx configurando un adeguato virtualhost.

### Esempio di installazione semplice su XAMPP

#### Step 1)

Copiare la cartella ```blog_cms``` in ```C:\xampp\htdocs\``` 

#### Step 2)

Riavviare XAMPP, aprire il browser e navigare al seguente url:

```localhost/blog_cms```

### Esempio di installazione su XAMPP con dominio personalizzato

#### Step 1)

Copiare la cartella ```blog_cms``` in ```C:\xampp\htdocs\```  

#### Step 2)
Aprire ed aggiungere la seguente riga al file ```hosts``` in ```C:\Windows\System32\drivers\etc```:

```
127.0.0.1       blogcms.local
```

#### Step 3) 

Aggiungere alla fine del file ```C:\xampp\apache\conf\extra\httpd-vhosts.conf``` le seguenti righe:

```
<VirtualHost *:80>
    DocumentRoot "C:\xampp\htdocs\blog_cms"
    ServerName blogcms.local
</VirtualHost>
```

#### Step 3.1) 


Verificare che nel file ```C:\xampp\apache\conf\httpd.conf``` sia abilitata la seguente ```Include``` (Approssimativamente alla riga 500): 

```
#Virtual host
Include conf/extra/httpd-vhosts.conf
```

#### Step 4) 

Riavviare XAMPP, aprire il browser e navigare al seguente url:

```blogcms.local```