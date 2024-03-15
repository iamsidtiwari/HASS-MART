<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>


<script>
    var data = {
    service_id: 'service_3udb257',
    template_id: 'template_stk7v1m',
    user_id: 'lA07KOx-7K3ZFsx_j',
    template_params: {
       name:localStorage.getItem("username")
       email:localStorage.getItem("useremail")
    }
    console.log(template_params);
};
 
$.ajax('https://api.emailjs.com/api/v1.0/email/send', {
    type: 'POST',
    data: JSON.stringify(data),
    contentType: 'application/json'
}).done(function() {
    alert('Your mail is sent!');
    // <?php header('location:login.php'); ?>
}).fail(function(error) {
    alert('Oops... ' + JSON.stringify(error));
    // <?php header('location:register.php'); ?>

});
</script>