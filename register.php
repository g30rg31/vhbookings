<?php
session_start();

/*********************************************************************************************************************
 ***  register.php                                                                                                 ***
 ***  part of VH Management system                                                                                 ***
 ***                                                                                                               ***
 ***                                                                                                               ***
 ***                                                                                                               ***
 ***                                                                                                               ***
 ***                                                                                                               ***
 ***  George Thompson                                                                                              ***
 *********************************************************************************************************************/

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Village Hall Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <style>
        .bs-example {
            margin: 20px;
        }
    </style>

<style>
	td {
	vertical-align: top;
	text-aligh: left;
	}
</style>



<?php
$rolear = array("user", "admin" );
?>

<script>

function processmuo(action) {

  sendaction = action.substring(5,2);
  senduser = document.getElementById("email").value;

   var xmlhttp = new XMLHttpRequest();
   xmlhttp.onreadystatechange = function() {
     if (this.readyState == 4 && this.status == 200) {
        respAr=JSON.parse(this.responseText);
	  if(respAr[1] == "Failed" ) {
		alert(respAr[2]);
	  } else {
		alert(respAr[2]);
	  }
        }
   };
   xmlhttp.open("GET", "processmuo.php?action=" + action + "&id=" + senduser, true);
   xmlhttp.send();

}
</script>




<script>
function seluser(ident) {

   var xmlhttp = new XMLHttpRequest();
   xmlhttp.onreadystatechange = function() {
     if (this.readyState == 4 && this.status == 200) {
        localStorage.setItem("userdata", this.responseText);
        userdataAr = JSON.parse(localStorage.getItem("userdata"));
	document.getElementById("emailhint").innerHTML="";
	document.getElementById("namehint").innerHTML="";
        nameAr = userdataAr["name"].split(" ");
        document.getElementById("firstname").value=nameAr[0];
        document.getElementById("lastname").value=nameAr[1];
        document.getElementById("postcode").value=userdataAr["postcode"];
        document.getElementById("email").value=userdataAr["email"];
        document.getElementById("address1").value=userdataAr["address1"];
        document.getElementById("organisation").value=userdataAr["member"];
        document.getElementById("phone").value=userdataAr["phone"];
        document.getElementById("address2").value=userdataAr["address2"];
        document.getElementById("address3").value=userdataAr["address3"];
        document.getElementById("address4").value=userdataAr["address4"];
        document.getElementById("urole").value=userdataAr["wrole"];
        document.getElementById(userdataAr["urole"]).selected=true;
        }
   };
   xmlhttp.open("GET", "getcustprofile.php?name=" + ident, true);
   xmlhttp.send();

}
</script>

<script>
function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

function checkCookie() {

    var myParam = location.search.split('err=')[1] ? location.search.split('err=')[1] : '';
    if (myParam !='') document.getElementById("errmessage").innerHTML="Onboarding failed, please contact the Adminstrator or Register below.";

  var username = getCookie("Loggedon");
  custname = username.replace("+", " ");
  if (custname != "") {
	var userrole = getCookie("Role");

	if (userrole=="admin") {
		document.getElementById("usermessage").innerHTML = "You are logged on as an administrator. Your can manage user's profiles here";
		document.getElementById("formtitle").innerHTML = "Update Profile";
		document.getElementById("addnewcust").hidden = false;
                document.getElementById("newmsg").hidden = false;
		document.getElementById("passwdinput").hidden = true;

	} else {
		document.getElementById("usermessage").innerHTML = "You can update your Profile here";
		document.getElementById("addnewcust").hidden = true;
                document.getElementById("newmsg").hidden = true;
		document.getElementById("passwdinput").hidden = true;
//		document.getElementById("urole").value="user";

	}
//   alert("Your are logged on as " + custname + ". You can edit your profile");

	seluser(custname);

  }
}

</script>

<script>
function checkform(butpressed) {
//	alert("pressed" + butpressed);
	document.getElementById("errmessage").innerHTML = "";
	numErrs = 0;
	var fields=document.getElementsByClassName('checkme');
//	alert(fields.length);
//	fields.forEach(validatefield);
	for (i=0;i<fields.length;i++) {
//		alert(fields[i].id);
		document.getElementById(fields[i].id).style.borderColor = "black";
//Are all fields completed?
		numErrs += validatefield(fields[i].id,i);
		}
	if (numErrs > 0 ) {
		document.getElementById("errmessage").innerHTML = numErrs + " input fields need completing";
		return;
	} else { 
		document.getElementById("errmessage").innerHTML = ""; 

		var username = getCookie("Loggedon");
		if (username == "" ) {

			if (document.getElementById("password1").value != document.getElementById("password2").value ) {
				document.getElementById("errmessage").innerHTML = "Passwords do not match";
				document.getElementById("password1").style.borderColor = "red";
				document.getElementById("password2").style.borderColor = "red";
				document.getElementById("errmessage").focus();
				return;
				}
			if (!checkPassword(document.getElementById("password1").value )) {
				document.getElementById("errmessage").innerHTML = "Passwords strength does not meet standard";
				document.getElementById("password1").style.borderColor = "red";
				document.getElementById("errmessage").focus();
				return;
				}
			}
		if (!is_email(document.getElementById("email").value)) {
                        document.getElementById("errmessage").innerHTML = "Please enter a valid email address";
			document.getElementById("email").style.borderColor = "red";
			document.getElementById("errmessage").focus();
                        return;
                        }
		if (!document.getElementById("tcs").checked) {
                        document.getElementById("errmessage").innerHTML = "Please accept Terms and Conditions";
                        elem = document.getElementById("tcs");
//			elem.parentNode.style.color = (elem.checked) ? 'black' : 'red';
			document.getElementById("errmessage").focus();
			return;
                        }

		if ( document.getElementById("errmessage").innerHTML == "" ) {

			if (getCookie("Loggedon") == "" || document.getElementById("addnewcust").checked ) {
				serveraction = "new";
			} else {
				serveraction = "update";
			}

// alert ( document.getElementById("urole"));
			if (typeof document.getElementById("urole") === "undefined" ) {
				wrole = "user";
			} else {
				wrole = document.getElementById("urole").value;
			}


// store inputs
			var postdata = {
//				action: serveraction,
				name:  document.getElementById("firstname").value + " " + document.getElementById("lastname").value,
				email: document.getElementById("email").value,
	                        organisation: document.getElementById("organisation").value,
        	                phone: document.getElementById("phone").value,
	                        addr1: document.getElementById("address1").value,
				addr2: document.getElementById("address2").value,
				addr3: document.getElementById("address3").value,
				addr4: document.getElementById("address4").value,
	                        postcode: document.getElementById("postcode").value,
	                        role:  wrole // document.getElementById("urole").value
				};

			var userstr = JSON.stringify(postdata);
			localStorage.setItem("userdata",userstr);
			postdata.password = document.getElementById("password1").value;
			postdata.role = wrole;
			postdata.action = serveraction;

			var xmlhttp = new XMLHttpRequest();
                        xmlhttp.onreadystatechange = function() {
	                        if (this.readyState == 4 && this.status == 200) {
					jsonRespAr=JSON.parse(this.responseText);
// alert(jsonRespAr.text);	
					document.getElementById("errmessage").innerhtml="";
        	                        document.getElementById("usermessage").innerHTML = jsonRespAr.text;
					document.getElementById("usermessage").focus();
					}
                       	};
                        xmlhttp.open("POST", "setcustprofile.php", true);
                        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                        xmlhttp.send("postdata=" + JSON.stringify(postdata));



		
		}
	}



}
</script>

<script>
function is_email(email){      
var emailReg = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
return emailReg.test(email); } 
</script>

<script>
function checkPassword(inputtxt) {
	var pwdform =  /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).{8,15}$/;
//	var pwdform =  /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,15}$/;
	if(pwdform.test(inputtxt))
	{
//		alert('Correct, try another...')
		return true;
	} else {
//		alert('Wrong...!')
		return false;
	}
}
</script>
<script>
function validatefield(itemid, index) {
numErrs2 = 0;
//alert( itemid + ":" + index);
//alert(document.getElementById(itemid).value);
if (document.getElementById(itemid).value == "" ) {
	document.getElementById(itemid).style.borderColor = "red"; 
//	document.getElementById("errmessage").innerHTML = "Please complete your input"; 
	numErrs2++;
	}
return(numErrs2);
}
</script>




<script>
function resetform() {

	document.getElementById("errmessage").innerHTML = "";
        var fields=document.getElementsByClassName('checkme');

        for (i=0;i<fields.length;i++) {
                document.getElementById(fields[i].id).value = "";
                }
        document.getElementById("organisation").value = "";
	document.getElementById("address3").value = ""; 
	document.getElementById("address4").value = "";
	document.getElementById("tcs").checked = false;
}
</script>

<script>
function getemailhint(str) {
  if (str.length == 0) {
    document.getElementById("email").innerHTML = "";
    return;
  } else {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
//        document.getElementById("emailhint").value = this.responseText;
	document.getElementById("emailhint").innerHTML = this.responseText;
      }
    };
    xmlhttp.open("GET", "getemailhint.php?q=" + str, true);
    xmlhttp.send();
  }
}
</script>

<script>
function getnamehint(str) {
  if (str.length == 0) {
    document.getElementById("firstname").innerHTML = "";
    return;
  } else {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
//        document.getElementById("emailhint").value = this.responseText;
	document.getElementById("namehint").innerHTML = this.responseText;
      }
    };
    xmlhttp.open("GET", "getnamehint.php?q=" + str, true);
    xmlhttp.send();
  }
}
</script>


<script>
// Self-executing function
(function() {
    'use strict';
    window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();
    </script> 
</head> 

<body onload="checkCookie()"> 

<?php require("head.php"); ?> <input type="hidden" name="cid" value= <?php echo $params["id"]; ?> >

	<p id="displace" class="col-sm-9 offset-sm-3"></p>

    <div class="bs-example" id="mainframe">
        <h2 class="border-bottom pb-3 mb-4" id="formtitle">Registration Form</h2>
        <form  action="">
	<input type="text" hidden name="cid" value=" <?php echo $custid; ?> ">
	
            <div class="form-group row">
		<div class="col-sm-3">
		        <p>Your login will be your email address and will be used for bookings and invoices.</p>
		</div>
		<div class="col-sm-9">
	           <?php if ($_SESSION["role"] == "admin") {
			$muoAr = array( array("du", "Delete User"),array("rt","Remove Token"),array("su","Set to Unconfirmed"), array("fb","Show all Future Bookings"), array("wa","Set to Booking Adminstrator"),array("ha","Open Access to Hall"),array("mu","Make normal user"));
//			echo sizeof($muoAr);
//        	        echo '<div class="form-group row">';
//                	echo '<label class="col-sm-3 col-form-label" for="urole">User Role:</label>';
                        echo '<div class="col-sm-9 form-inline form-group">';
                        echo '<select class="form-control" name="muoption" id="muoption">';
                               for ($i=0; $i<sizeof($muoAr); $i++) {
//                                $rrole == $_SESSION["role"] ?  $sel = "selected" : $sel = "";
//				$i==5 ? $muodis="disabled" : $muodis =""; 
                                echo '<option id="' .  $muoAr[$i][0] . '" onclick="processmuo(this.id)" ' . $muodis . '>' . $muoAr[$i][1] . '</option>';
                                }
                         echo '</select>';
//			echo "<span>  Defaults to user.</span>";
                echo '</div>';
        echo '</div>';
        }  

?>

			<h4 class="text-danger" id="errmessage"></h4>
		</div>
	    </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="inputEmail">Email Address:</label>
                <div class="col-sm-9">
                    <input type="text" class="checkme form-control" autocomplete="off" id="email" name="inputEmail" placeholder="Email Address" onfocus="this.value=''" onkeyup="getemailhint(this.value)" >
		    <p id ="emailhint"></p>
                </div>
            </div>
	    <div class="form-group-row">
			<div class="offset-sm-3 col-sm-9">
                        <input type="checkbox" class="mr-1" id="addnewcust" name ="addnewcust" hidden><span id= "newmsg" hidden>Check this box to add a new Customer</span>
                        </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="firstName">First Name:</label>
                <div class="col-sm-9">
                    <input type="text" class="checkme form-control" id="firstname" name="firstName" placeholder="First Name" onfocus="this.value=''" onkeyup="getnamehint(this.value)" >
		    <p id="namehint"></p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="lastName">Last Name:</label>
                <div class="col-sm-9">
                    <input type="text" class="checkme form-control" id="lastname" name="lastName" placeholder="Last Name" >
                </div>
            </div>
	    <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="organisation">Business Name:</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="organisation" name="organisation" placeholder="Business name to appear on invoices (Optional)" >
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="phoneNumber">Mobile Number:</label>
                <div class="col-sm-9">
                    <input type="text" class="checkme form-control" id="phone" name="phoneNumber" placeholder="Mobile Phone Number, format like 071234567890" >
                </div>
            </div>
          
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="postalAddress">Postal Address:</label>
                <div class="col-sm-9">
                    <input type="text" class="checkme form-control" id="address1" name="address1" placeholder="Address Line 1" >
                    <input type="text" class="checkme form-control" id="address2" name="address2" placeholder="Address Line 2" >
                    <input type="text" class="form-control" id="address3" name="address3" placeholder="Address Line 3 (Optional)" >
                    <input type="text" class="form-control" id="address4" name="address4" placeholder="Address Line 4 (Optional)" >
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="ZipCode">Post Code:</label>
                <div class="col-sm-9">
                    <input type="text" class="checkme form-control" id="postcode" name="postCode" placeholder="Post Code" >
                </div>
            </div>
	<div id="passwdinput">
   	    <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="Password1">Password:</label>
                <div class="col-sm-9">
		    <input type="password" class="form-control" id="password1" name="Password1" placeholder="Password" >
                </div>
            </div>
	    <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="Password2">Confirm Password:</label>
                <div class="col-sm-9">
		    <input type="password" class="form-control" id="password2"name="Password2" placeholder="Confirm Password" >
		    <p>Passwords must be at least 8 characters long and contain at least one uppercase, one lowercase, and one number but cannot start with a number.</p>
                </div>
	    </div>

</div>
            <?php if ($_SESSION["role"] == "admin") {
                echo '<div class="form-group row">';
                echo '<label class="col-sm-3 col-form-label" for="urole">User Role:</label>';
                        echo '<div class="col-sm-9 form-inline form-group">';
                        echo '<select class="form-control" name="urole" id="urole">';
                                foreach ($rolear as $rrole) {
//                                $rrole == $_SESSION["role"] ?  $sel = "selected" : $sel = "";
                                echo '<option value="' .  $rrole . '" id="' . $rrole . '">' . $rrole . '</option>';
                                }
                         echo '</select>';
//			echo "<span>  Defaults to user.</span>";
                echo '</div>';
        echo '</div>';
        }  else {
                echo '<p id="urole" value = "user"></p>';
        }

?>
			
            <div class="form-group row">
                <div class="col-sm-9 offset-sm-3">
                    <label class="checkbox-inline">
                        <input type="checkbox" class="mr-1" id="tcs" name ="TCs" value="agree" > I agree to the <a href="https://www.eastmeonvillagehall.co.uk/booking/terms-and-conditions/">Terms and Conditions.</a>
                    </label>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-9 offset-sm-3">
			<button type="button" class="btn btn-primary" onclick = "checkform(this.id)" id="01" value="Submit">Submit</button>
                        <button type="button" class="btn btn-secondary" onclick = "resetform(this.id)" id="02" value="Reset">Reset</button>
                </div>
            </div>
        </form>
	<h4 id="usermessage"></h4>	

    </div> <?php require("foot.html"); ?>
</div>
</body>
</html>
