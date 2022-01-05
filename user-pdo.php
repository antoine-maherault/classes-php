<!DOCTYPE html>
<html>
 <head>
 <title>Classes</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
 </head>
 <body>
  <header>
  </header>
  <main>  
  <?php

  ////////////////////// Create class USER  ////////////////////// 

  class userpdo{

  // attributes _______________________

  private $id;
  public $login;
  public $email;
  public $firstname;
  public $lasname;
  public $bdd;
  public $users;

  // methods _______________________

  function __construct(){
    // connect to DB
    $server = "localhost";
	  $username = "root";
	  $password = "root";
	  $database = "classes";
    $dsn = "mysql:host=$server;dbname=$database;charset=UTF8";
    $this->bdd = new PDO($dsn, $username, $password);
    // session start 
    session_start();
    // users details 
    $sql = "SELECT * FROM utilisateurs" ;
    $query = $this->bdd->prepare($sql);
    $query -> execute();
    $this -> users = $query->fetchAll();
  }
  
  function register($login,$password,$email,$firstname,$lastname){ //add tests no same login
    // controls 
    foreach($this -> users as $user){
      if ($login == $user[1]){
        $stop = 1;
      }
    }
    if($login == NULL || $password == NULL || $email == NULL || $firstname == NULL || $lastname == NULL){
      $stop == 1;
    } 
    // query 
    if ($stop == 0){
      $bdd=$this->bdd;
		  $sql = "INSERT INTO utilisateurs(login,password,email,firstname,lastname) VALUES (:login, :password, :email, :firstname, :lastname)";
      $prepare = $bdd->prepare($sql);
      $execute = $prepare->execute([':login' => $login , ':password' => $password, ':email' => $email, ':firstname' => $firstname, ':lastname' => $lastname]);

      // $sql = "INSERT INTO utilisateurs(login, password, email, firstname,lastname) VALUES ('$login', '$password', '$email', '$firstname','$lastname')";
      // $query = $this->bdd->query($sql);
      // feedback 
      return "
        <table style='text-align:center'>
          <theader>
            <th>login</th>
            <th>password</th>
            <th>email</th>
            <th>firstname</th>
            <th>lastname</th>
          </theader>
          <tbody>
            <td> $login </td>
            <td> $password </td>
            <td> $email </td>
            <td> $firstname </td>
            <td> $lastname </td>
          </tbody>
        </table>
      ";
    }
    else{
      return "error";
    }
  }

  function connect($login,$password){
      // auth
      foreach($this -> users as $user){
          if ($login == $user[1] && $password == $user[2]){
              $_SESSION["connected"] = $login ;
              // fill attributes 
              $this -> login = $login;
              $this -> email = $user[3];
              $this -> firstname = $user[4];
              $this -> lastname = $user[5];
              // feedback
              return $this -> login . " is connected </br>";
          }
      }
  }

  function disconnect(){
    session_destroy();
    $this -> login = "";
  }

  function delete(){
    $login = $this->login;
    $bdd=$this->bdd;
    $sql = "DELETE FROM `utilisateurs` WHERE `login` = :login";
    $prepare = $bdd->prepare($sql);
    $execute = $prepare->execute([':login' => $login]);
    session_destroy();
    $this -> login = NULL;
    $this -> email = NULL;
    $this -> firstname = NULL;
    $this -> lastname = NULL;    
    return $login . " was deleted succesfully";
  }

  function update($login,$password,$email,$firstname,$lastname){
    foreach($this -> users as $user){
      if ($login == $user[1]){
        $stop = 1;
      }
    }
    if($login == NULL || $password == NULL || $email == NULL || $firstname == NULL || $lastname == NULL){
      $stop == 1;
    } 
    // update user details 
    if ($stop == 0 && isset($_SESSION["connected"])){
      $log = $this->login;
      $bdd=$this->bdd;
      $sql = "UPDATE `utilisateurs` SET login = :login, password = :password, email = :email, firstname = :firstname,lastname = :firstname WHERE `login` = :log";
      $prepare = $bdd->prepare($sql);
      $execute = $prepare->execute([':log' => $log, ':login' => $login, ':password' => $password, ':email' => $email, ':firstname' => $firstname, ':lastname' => $lastname]);
      $this -> login = $login;
      $this -> email = $email;
      $this -> firstname = $firstname;
      $this -> lastname = $lastname;
      return $log . " was updated succesfully";
    }
    else{
      return "error";
    }
  }

  function isConnected(){
    return isset($_SESSION["connected"]);
  }

  function getAllInfos(){
    return "
    <table style='text-align:center'>
      <theader>
        <th>login</th>
        <th>email</th>
        <th>firstname</th>
        <th>lastname</th>
      </theader>
      <tbody>
        <td> $this->login </td>
        <td> $this->email </td>
        <td> $this->firstname </td>
        <td> $this->lastname </td>
      </tbody>
    </table>
  ";
  }
  function getLogin(){
    return $this->login;
  }
  function getEmail(){
    return $this->email;
  }
  function getFirstname(){
    return $this->firstname;
  }
  function getLastname(){
    return $this->lastname;
  }
}

/////////////////// TESTS & DISPLAY ///////////////////  

  $user = new Userpdo();

  //_________Register _________//
    // echo $user->register("Junei","X","X","X","X");

  //_________Connect _________//
    echo $user->connect("Juneo","O");

  //_________Delete _________//
    // echo $user->delete();

  //_________Update _________//
    // echo $user->update("Juneo","O","O","O","O");

  //_________isConnected _________//
    // echo $user->isConnected();

  //_________getAllInfos _________//
    // echo $user->getAllInfos();

  //_________GetLogin _________//
    // echo $user->getLogin();

  //_________GetFirstname _________//
    // echo $user->getFirstname();

  //_________GetLastname _________//
    // echo $user->getLastname();

  ?>
  </main>
  <footer>
  </footer>
</body>
</html>