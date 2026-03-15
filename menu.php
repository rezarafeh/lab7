<?php
    session_start();
    //check the user already logged in
    if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)){
            header('Location: signin.php');
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Local Restaurant</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- Custom CSS -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Local Restaurant</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="index.html">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="menu.html">Menu</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="contact.html">Contact Us</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="signout.php">Sign out</a>
        </li>
      </ul>
    </div>
  </nav>
  <!-- Homepage -->
  <div class="container">
    <div class="jumbotron">
      <h1 class="display-4 text-center">Welcome to Local Restaurant</h1>
      <p class="lead text-center">We serve the best food in town.</p>
      <hr class="my-4">
      <p class="text-center">Come dine with us and experience the taste of our delicious dishes.</p>
      <p class="text-center"><a class="btn btn-primary btn-lg" href="menu.html" role="button">View Menu</a></p>
    </div>
  </div>
  <!-- Menu Page -->
  <div class="container">
    <h1 class="text-center">Menu</h1>
    <table class="table table-bordered table-striped" id="items">
      <thead>
       <tr>
          <th>Item</th>
          <th>Description</th>
          <th>Price</th>
          <th>Pic</th>
          <th>Order</th>
        </tr>
      </thead>
      <tbody>
        
      </tbody>
    </table>    
  </div>
  <!-- Cart section -->
  <div class="row" id="cart">
    <div class="col-8">

    </div>
    <div class="col-2">
          <img class="img-fluid" src="images/cart.jpg" alt="Salad" width=50 height=50>
    </div>
    <div class="col-2">
        <h1 id="total" style="text-align: left;">$0</h1>
    </div> 
  </div>
  <!-- Add new items -->
  <div id="add-items">
    <div class="row">
      
      <div class="col-2">
          <label for="item">Item:</label>
          <input type="text" id="item" name="item" size="10">
      </div>
      <div class="col-4">
          <label for="description">Description:</label>
          <input type="text" id="description" name="description" size="50">
      </div>
      <div class="col-2">
        <label for="price">Price:</label>
        <input type="number" id="price" name="price" style="width: 4em">
      </div>
      <div class="col-4">
        <label for="pic">Select an image:</label>
        <input type="file" id="pic" name="pic">
      </div>
    </div>
    <div class="row">
      <div class="col-6">
        <button class="btn float-right" style="color:white; background-color:#1e8564"  id="addItem">Add Item</button>
      </div>
    </div>

  </div>
  <!-- Contact Page -->
  <div class="container" id="contact">
    <h1 class="text-center">Contact Us</h1>
    <form action="submit-form.php" method="post">
      <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" class="form-control" id="name" name="name" required>
      </div>
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="message">Message:</label>
        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>

<script>
  var total = 0 ;
  
  function calculateTotal(){
    let items = document.getElementById('items');
    let total = 0;
    var orders = document.getElementsByClassName("order");
    for (let i = 1 ; i < items.rows.length; i++){
      let price = items.rows[i].cells[2].innerText;
      total = total + price * orders[i-1].value;
    }
    document.getElementById("total").innerHTML='$'+total;
}
    
    
  
  // A global variable to store item data from files
  var itemData = [];
  $(document).ready(function() {
    //Read files to get the table of items ready
    
    readFiles();
   
    //Delete an item from the table
    $('#items').on('click', '.deleteBtn', function() {
    let pic = $(this).closest('tr').find('td:nth-of-type(4) img').attr('src');
    
		// Remove the corresponding row from the table
		$(this).closest('tr').remove();
    // Delete the file from the folder
    $.ajax({
        url: 'deleteitem.php?fileName='+pic,
        success: function() {
            console.log('File deleted.');
        }
    });
	});

	  $('#addItem').on('click', function() {
        let item = $('#item').val();
        let description = $('#description').val();
        let price = $('#price').val();
        let pic = $('#pic')[0].files[0];
        let fileExtension = pic.name.split('.')[1];
        var formData = new FormData();
        formData.append("files[]", pic);
        //Convert price to floating point if already not
        let priceAsFloat = xAsString = (price % 1 === 0) ? (price + ".0") : (price.toString());
        formData.append("Name",item);
        formData.append("Description",description);
        formData.append("Price",priceAsFloat);
        formData.append("Picture",pic.name);
           $.ajax({
              url: "additem.php",
              type: "POST",
              data: formData,
              processData: false,  
              contentType: false   
            }).done(function( data ) {
                $('#item').val("");
                $('#description').val("");
                $('#price').val("");
                $('#pic').val("");
                let newData = '{"Name":"'+item+'","Description":"'+description+'","Price":"'+price+'","Picture":"'+"uploads/"+ pic.name+'"}';
                addItemtoTable(JSON.parse(newData));
                console.log("File Upload Info:");
                console.log( data );
            });
			
  })
})
function readFiles() {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        let result = (this.responseText);
        // This function populates the table with read data
        showTableofItems(JSON.parse(result));
        var userType = '<?php echo $_SESSION["role"];?>';
    
        if (userType=='customer'){ // Hide admin sections
            $('#add-items').hide();
          }
        if (userType=='admin'){ //Hide customer sections
          $('#cart').hide();
          $('#contact').hide();
          //Hide the order coulmn
          // Get the index of the column to hide
          var columnIndex = $('#items th:contains("Order")').index();
          
          // Hide the column in the table header and body
          $('#items th:eq(' + columnIndex + ')').hide();
          $('#items td:nth-child(' + (columnIndex + 1) + ')').hide();
          //Add delete button to all rows
         // $('#items tbody tr').append('<td><button class="deleteBtn"  > <i class="fa fa-trash"></i></td>');
          
          }
            
      }
    };
    xmlhttp.open("GET", "readitems.php", true);
    xmlhttp.send();
  
}


  function addItemtoTable(itemData){
    
        let item = itemData.Name;
        let description = itemData.Description;
        let price = itemData.Price;
        let pic = itemData.Picture;
        let table = $('#items');
        let newRow = $('<tr>');
		    let cell1 = $('<td>').text(item);
        let cell2 = $('<td>').text(description);
        let cell3 = $('<td>').text(price);
        let cell4 = $('<td>');
        //Delete button
        var userType = '<?php echo $_SESSION["role"];?>';
        let cell5 = $('<button>').attr('class',"deleteBtn fa fa-trash");
        if (userType=='customer') // Delete button only for admin
                // Order for customers
                cell5 = '<td> <input type="number" class="order" style="width:50px" onchange="calculateTotal()"></input> </td>';
        let newImage = $('<img>').attr('src', pic).css({'width': '100', 'height': '100'});
        cell4.append(newImage);  
        newRow.append(cell1);
        newRow.append(cell2);
        newRow.append(cell3);
        newRow.append(cell4);
        newRow.append(cell5);
        

        table.append(newRow);
        
  }
  function showTableofItems(items){
    for(var i = 0; i < items.length; i++){
      addItemtoTable(items[i]);
    }

  }
</script>
</body>
</html>
