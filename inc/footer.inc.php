    <div class="container">

        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; Your Website 2018</p>
                </div>
            </div>
        </footer>

    </div>
    <!-- /.container -->


    <!-- Bootstrap core JavaScript -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.js"></script>
    <script>
      $('#menu_utilisateur').change(function() {

        var valeur = this.value;
        // alert(valeur);
        if(valeur == "s'inscrire") 
        {
          window.location = "inscription.php";
        }

        else if(valeur == "Se connecter") 
        {
           window.location = "connexion.php";
        } 
        else if(valeur == "Profil")
        {
          window.location = "profil.php";
        }
    });
    </script>

  </body>

</html>
