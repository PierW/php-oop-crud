<?php

include 'databaseinfo.php';
// var_dump($database); die();

class Persona
{
  private $nome;
  private $cognome;

  function __construct($nome, $cognome)
  {
    $this -> setName($nome);
    $this -> setCognome($cognome);
  }

  public function getName()
  {
    return $this -> nome;
  }

  public function setName($nome)
  {
    $this -> nome = $nome;
  }

  public function getCognome()
  {
    return $this -> cognome;
  }

  public function setCognome($cognome)
  {
    $this -> cognome = $cognome;
  }
}


/**
 *
 */
class Ospite extends Persona
{
  private $annodinascita;
  private $documenttype;
  private $documentnumber;

  function __construct($nome, $cognome, $annodinascita, $documenttype, $documentnumber)
  {
    parent::__construct($nome, $cognome);

    $this -> setAnnoNascita($annodinascita);
    $this -> setDocumentType($documenttype);
    $this -> setDocumentNumber($documentnumber);
  }

  public function getAnnoNascita()
  {
    return $this -> annodinascita;
  }

  public function setAnnoNascita($annodinascita)
  {
    $this -> annodinascita = $annodinascita;
  }

  public function getDocumentType()
  {
    return $this -> documenttype;
  }

  public function setDocumentType($documenttype)
  {
    $this -> documenttype = $documenttype;
  }

  public function getDocumentNumber()
  {
    return $this -> documentnumber;
  }

  public function setDocumentNumber($documentnumber)
  {
    $this -> documentnumber = $documentnumber;
  }

  public static function getAllOspiti($conn)
  {
    $sql = "
            SELECT name, lastname, date_of_birth, document_type, document_number
            FROM ospiti";

    $result = $conn ->query($sql);
    // var_dump($result); die();

    if ($result -> num_rows >0) {

      $ospiti = [];
      while ($row = $result -> fetch_assoc()) {

        $ospiti[] = new Ospite(
          $row["name"],
          $row["lastname"],
          $row["date_of_birth"],
          $row["document_type"],
          $row["document_number"]
        );
      }
      return $ospiti;
      // var_dump($ospiti); die ();
    }
    else {
      echo "0 risultati";
    }
  }
}

/**
 *
 */
class Pagante extends Persona
{
  private $address;

  function __construct($nome, $cognome, $address)
  {
    parent::__construct($nome, $cognome);
    $this -> setAddress($address);
  }

  public function setAddress($address)
  {
    $this -> address = $address;
  }

  public function getAddress()
  {
    return $this -> address;
  }

  public static function getAllPaganti($conn)
  {
    $sql = "
          SELECT name, lastname, address
          FROM paganti
          ORDER BY name ASC
          ";

      $result = $conn -> query($sql);
      // var_dump($result); die();

      if ($result -> num_rows > 0) {
        // code...
        $paganti = [];
        while ($row = $result -> fetch_assoc()) {
          // var_dump($row); die();
          // $paganti[] = $row;  NON FACCIAMO PIù COSì MA:
          $paganti[] = new Pagante(
            $row["name"],
            $row["lastname"],
            $row["address"]
          );
        }
        // var_dump($paganti); die();
        return $paganti;
      } else {
        echo "0 Risultati";
      }
  }

  public static function getEPaganti($conn)
  {
    $sql = "
            SELECT *
            FROM `paganti`
            WHERE name
            LIKE 'e%'
          ";

    $result = $conn -> query($sql);
    // var_dump($result); die();
    if ($result -> num_rows > 0) {

      $epersons = [];
      while ($row = $result -> fetch_assoc()) {
        // var_dump($row); die();
        $epersons[] = new Pagante(
          $row["name"],
          $row["lastname"],
          $row["address"]
        );
      }
      // var_dump($epersons); die();
      return $epersons;
    }
    else {
      echo "0 risultati";
    }
  }
}

// Inizio connessione database------------------
$conn = new mysqli($server, $user, $password, $database);
// var_dump($conn); die();

if ($conn -> connect_errno) {

  echo "Errore di connessione " . $conn -> connect_error;
  return;
}
//----------------------------------------------

$ospiti = Ospite::getAllOspiti($conn);
// var_dump($ospiti); die();
$paganti = Pagante::getAllPaganti($conn);
// var_dump($paganti); die();
$epaganti = Pagante::getEPaganti($conn);
// var_dump($epaganti); die();

$persona = new Persona("Pierpaolo", "Wurzburger");
$ospite = new Ospite("Pierpaolo", "Wurzburger", "1991", "CI", "3423543535");
$pagante = new Pagante("Pierpaolo", "Wurzburger", "Via Pinocchio");

// var_dump($persona); echo "<br>";
// var_dump($ospite); echo "<br>";
// var_dump($pagante);

foreach ($paganti as $pagante) {

  $nome = $pagante -> getName();
  $cognome = $pagante -> getCognome();
  $indirizzo = $pagante -> getAddress();

  echo "NOME: $nome || COGNOME: $cognome || INDIRIZZO: $indirizzo <br>";

}

foreach ($epaganti as $epagante) {

  $nome = $epagante -> getName();
  $cognome = $epagante -> getCognome();
  $indirizzo = $epagante -> getAddress();

  echo "<br><br>I NOMI CHE INIZIANO PER \"E\" SONO: <br>
        NOME: $nome || COGNOME: $cognome || INDIRIZZO: $indirizzo
        ";
}

$conn -> close();
 ?>
