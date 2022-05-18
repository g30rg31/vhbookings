<head>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

  <style>
  .modal-header, h4, .close {
    background-color: #0275d8;
    color:white !important;
    text-align: center;
    font-size: 20px;
  }
  .modal-footer {
    background-color: #f9f9f9;
  }
  </style>




<?php

if ($_SESSION["role"] == "admin")  {
    $menuhid="";
}else{
    $menuhid="hidden";
}

if ($_SESSION["loggedon"] == "yes" ) {
    $mname = $_SESSION["username"];
    $dislon = "hidden";
    $dislof = "";
} else {
    $mname = "Log In";
    $dislof = "hidden";
    $dislon = "";
}

?>

<div style="text-align:center">
<h1>East Meon Village Hall</h1>
</div>
<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
<div class="navbar-header">
  <a class="navbar-brand" href="index.php">
   <img src="emvh.jpeg" alt="Village Hall" style="width:200px;">
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>
  </div>
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
      <ul class="nav navbar-nav">
          <li class="nav-item">
              <a class="nav-link" href="calendar.php">Bookings</a>
          </li>
          <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown" <?php echo $menuhid; ?> >Administration</a>
              <div class="dropdown-menu">
		  <a class="dropdown-item" id="pendingcusts" onclick="adminmenu(this.id)">New Registrations</a>
                  <a class="dropdown-item" id="pendingbooks" onclick="adminmenu(this.id)">New Bookings</a>
                  <a class="dropdown-item" id="pendingchgs" onclick="adminmenu(this.id)">Booking Change Requests</a>
                  <a class="dropdown-item" id="register" href="register.php">Manage Users</a>
              </div>
          </li>

          <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="findrop" data-toggle="dropdown" <?PHP echo $menuhid; ?> >Finances</a>
              <div class="dropdown-menu">
                  <a class="dropdown-item" id="bookingvalue" onclick="adminmenu(this.id)">Current Year Bookings Value</a>
                  <a class="dropdown-item" id="bookingvalue1" onclick="adminmenu(this.id)">Bookings Value comparison by Year</a>
                  <a class="dropdown-item" id="depositsheld" onclick="adminmenu(this.id, 0)">Deposits Held</a>
                  <a class="dropdown-item" id="accounts" onclick="adminmenu(this.id, 0)">Accounts</a>
                  <a class="dropdown-item" id="log" onclick="adminmenu(this.id)">Audit Trail</a>
              </div>
          </li>

          <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="invdrop" data-toggle="dropdown" <?PHP echo $menuhid; ?> >Invoices</a>
              <div class="dropdown-menu">
		  <a class="dropdown-item" id="listallinvs" onclick="invmenu(this.id ,0)">All Invoices</a>
                  <a class="dropdown-item" id="draftinvoices" onclick="invmenu(this.id, 0)">Draft Invoices</a>
                  <a class="dropdown-item" id="unpaidinvoices" onclick="invmenu(this.id, 0)">Unpaid Invoices</a>
                  <a class="dropdown-item" id="oldinvoicess" onclick="invmenu(this.id, 0)">Invoices > 30 days</a>
              </div>
          </li>

          <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="invdrop" data-toggle="dropdown" <?PHP echo $menuhid; ?> >System</a>
              <div class="dropdown-menu">
                  <a class="dropdown-item" id="dashboard" href="dashboard.php">Dashboard</a>
                  <a class="dropdown-item" id="logs" href="logs.php">Activity Log</a>
                  <a class="dropdown-item" id="sysconfig" onclick="adminmenu(this.id)">System Configuration</a>
              </div>
          </li>

          <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="invdrop" data-toggle="dropdown" hidden <?PHP echo $menuhid; ?> >Downloads</a>
              <div class="dropdown-menu">
                  <a class="dropdown-item" id="customerfile" href="customerfile.csv" download="customerlist.csv">Customers</a>
                  <a class="dropdown-item" id="bookingsfile" href="bookingsfile.csv" download="bookinglist.csv">Bookings</a>
		  <a class="dropdown-item" id="invoicefile" href="invoicefile.csv" download="invoicelist.csv">Invoices</a>
              </div>
          </li>


          <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">Links</a>
              <div class="dropdown-menu">
                  <a class="dropdown-item" href="https://www.eastmeonvillagehall.co.uk">Village Hall Website</a>
                  <a class="dropdown-item" href="https://eastmeonpc.org.uk">Parish Council</a>
                  <a class="dropdown-item" href="https://www.meonmatters.com">Meon Matters</a>
                  <a class="dropdown-item" href="https://www.bbc.co.uk/weather">Weather</a>
              </div>
          </li>
      </ul>

    <ul class="nav navbar-nav ml-auto">


      <li class="nav-item" <?php echo $dislon; ?> >
      		<a class="nav-link" href="login.php">Log In</a>
      </li>

      <li class="nav-item dropdown" <?php echo $dislof; ?> >
	<a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
		<?php echo $mname; ?>
	</a>
        <div class="dropdown-menu">
                <a class="dropdown-item" href="login.php" <?php echo $dislon; ?> >Log In</a>
                <a class="dropdown-item" href="signout.php" <?php echo $dislof; ?> >Log Out</a>
                <a class="dropdown-item" href="register.php" <?php echo $dislof; ?> >My Profile</a>
		<a class="dropdown-item" href="chgpasswd.php" <?php echo $dislof; ?> >Change Password</a>
                <a class="dropdown-item" href="about.php" hidden >About</a>
        </div>
      </li>


    </ul>
  </div>
</nav>

<script>
$(document).ready(function(){
  $('[data-toggle="popover"]').popover();
});
</script>


<script>
function goback() {
        document.getElementById("mainframe").hidden= false;
        document.getElementById("displace").hidden= true;
        document.getElementById("invpages").hidden= true;
}
</script>

<script>
function adminmenu(service) {

document.getElementById("mainframe").hidden=true;
document.getElementById("displace").hidden=false;
document.getElementById("invpages").hidden=true;
//chart.destroy();

var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("displace").innerHTML = this.responseText;
	contentAr = JSON.parse(this.responseText);
	callfunction = contentAr.action;
//console.log(callfunction);
	window[callfunction](contentAr);

      }
    };
 //alert(service + ".php");
    xmlhttp.open("GET", service + ".php", true);
    xmlhttp.send();


}
</script>
<script>
var chart;
function  showbookingvalue(contentAr) {

document.getElementById("displace").innerHTML ="";
document.getElementById("invpages").hidden=true;
//alert(contentAr["Sep"]);
valSeries = "[" + (contentAr["Sep"]  == void(0) ? 0 : contentAr["Sep"])
		+ "," +( contentAr["Oct"]  == void(0) ? 0 : contentAr["Oct"])
		+ "," +(contentAr["Nov"] == void(0) ? 0 : contentAr["Nov"] )
		+ "," +(contentAr["Dec"] == void(0) ? 0 : contentAr["Dec"])
		+ "," +(contentAr["Jan"] == void(0) ? 0 : contentAr["Jan"])
		+ "," +(contentAr["Feb"] == void(0) ? 0 : contentAr["Feb"])
		+ "," +(contentAr["Mar"] == void(0) ? 0 : contentAr["Mar"])
		+ "," +(contentAr["Apr"] == void(0) ? 0 : contentAr["Apr"])
		+ "," +(contentAr["May"] == void(0) ? 0 : contentAr["May"])
		+ "," +(contentAr["Jun"] == void(0) ? 0 : contentAr["Jun"])
                + "," +(contentAr["Jul"] == void(0) ? 0 : contentAr["Jul"])
                + "," +(contentAr["Aug"] == void(0) ? 0 : contentAr["Aug"])
		+ "]";
//console.log(valSeries);
var options = {
  chart: {
    height: 350,
    type: 'bar'
  },
  title: {
    text: "Value of hall hire charges for this financial year =£" + contentAr["total"]
  },
  series: [{
    name: 'Value of Bookings',
    data: JSON.parse(valSeries)
    }
  ],
  xaxis: {
    categories: ["Sep","Oct","Nov","Dec","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug"]
    }
  }

chart = new ApexCharts(document.querySelector("#displace"), options);

chart.render();

var buttonTag = document.createElement("div");
buttonTag.innerHTML =  '<input id="dismissbut" class="btn btn-primary" value="Close Chart" onclick="dismissChart(chart)" >';
document.body.appendChild(buttonTag);

//document.body.removeChild(buttonTag);

}

function dismissChart(chart) {

chart.destroy();  

var elem = document.getElementById("dismissbut");
elem.parentNode.removeChild(elem);

document.getElementById("mainframe").hidden = false;
}


</script>





<script>
function  showbookingvalue1(contentAr) {
//chart.destroy();
document.getElementById("displace").hidden=false;
document.getElementById("displace").innerHTML ="";

//draw empty chart

var options = {
  chart: {
    height: 350,
    type: 'bar'
    },
  dataLabels: {
    enabled: false
  },
  title: {
    text: "Value of hall bookings by year"
  },
  series:  [],
  noData: {
    text: 'Please wait, data being  fetched from server'
    },
  xaxis: {
    categories: ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"]
    }
  }

chart = new ApexCharts(document.querySelector("#displace"), options);

chart.render();

// end of draw wmpty chart

seriesstr = "[";
numyears = Object.keys(contentAr).length-1;
years = Object.keys(contentAr);
months = Object.keys(contentAr[years[0]]);
//valseries1 = 
//console.log(months[0]);
var valseries = {};
for (i=0; i < numyears; i++ ) {
	valseries[i] = "[";
	
	for (j=0; j <12; j++) {
//alert(contentAr["Sep"]);
		if (  j ) {
			valseries[i] = valseries[i] + "," + (contentAr[years[i]][months[j]]  == void(0) ? 0 : contentAr[years[i]][months[j]]);
		} else {
			valseries[i] = valseries[i] + (contentAr[years[i]][months[j]]  == void(0) ? 0 : contentAr[years[i]][months[j]]);
		}

	} // for j
	valseries[i] = valseries[i] + "]";
	console.log(valseries[i]);
	if (i) {
		seriesstr = seriesstr + ',{ ' + '"name": ' + years[i] + ', "data": ' + (valseries[i]) + '}';
	} else {		
		seriesstr = seriesstr + '{ ' + '"name": ' + years[i] + ', "data": ' + (valseries[i]) + '}';
	}

}  // for i

//console.log(seriesstr);



seriesstr = seriesstr + "]";
console.log(seriesstr);
console.log(JSON.parse(seriesstr));
/* this works 
var options = {
  chart: {
    height: 350,
    type: 'bar'
    },
  dataLabels: {
    enabled: false
  },
  title: {
    text: "Value of hall bookings by year"
  },
  series:  JSON.parse(seriesstr)
  ,
  xaxis: {
    categories: ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"]
    }
  }

var chart = new ApexCharts(document.querySelector("#displace"), options);

chart.render();
*/
//alert(seriesstr);

chart.updateSeries(JSON.parse(seriesstr));

var buttonTag = document.createElement("div");
buttonTag.innerHTML =  '<input id="dismissbut" class="btn btn-primary" value="Close Chart" onclick="dismissChart(chart)" >';
document.body.appendChild(buttonTag);


}
</script>



<script>

var token = "";
var pin =   "000000";
var sms =   "0";

function accept(bookid) {

var mailformat = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
// if bookid is an INT, then its a booking. If its a string its an email.

if ( !bookid.match(mailformat)) {

// update booking to confirmed
// set pincode and tokens - generate pin code on server and text to user
// pincodes go in bookings, tokens go in customers need to update readwgld
// update customers to confirmed. cust is unconfirmed if no access has bene granted
// generate invoice
// accepting includes all child bookings

// confirm the booking and return the booking pincode
var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        pinAr = JSON.parse(this.responseText);
  //      console.log(pinAr);
	sms = confirm("Pincode " + pinAr.pin + " assigned to booking: " + pinAr.booking + ".  Send Pincode to "  + pinAr.customer + " via SMS?");
//console.log(sms);
	// if user OKs , send the pin
	if (sms) {
		sendpin(pinAr.pin);
	}

	if (pinAr.token == "" || pinAr.token == "undefine" ) {
		var token = prompt("User does not have a access token. Allocate token to user?", "Touch token on keypad");
		if ( token && token.length == 8 ) {
			svToken = token;
		} else {
			svToken = "";
		}
	} else svToken = pinAr.token;
//console.log(pinAr.token);
	var chargeable = confirm("Make this a chargeable booking");
	if ( chargeable ) {
		svcharge = 1;
		var deposit = 	prompt("Amount of deposit to be added to invoice","100.00");
		if (deposit > 0) {
			svdeposit = deposit;
		} else {
			svdeposit=0;
		}
		var invoice = confirm("Generate the invoice?");
	        if ( invoice ) {
	                invoiceNumber = createinvoice(pinAr["email"], bookid);
		}

	} else {
		svcharge=0;
		svdeposit=0;
	}
	saveToken(svToken, pinAr.customer, "", svdeposit, bookid, svcharge);

	adminmenu("pendingbooks");
      }
    };
    xmlhttp.open("GET", "confbooking.php?q=" + bookid, true);
    xmlhttp.send();
} // is noit emailr

else {

// confirm the booking and return the booking pincode
var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        pinAr = JSON.parse(this.responseText);

	if (pinAr.token == "" || pinAr.token == "undefine" ) {
		var token = prompt("User does not have an access token. Allocate token to user?", "Touch token on keypad");
		if ( token ) {
			svToken = token;
		} else {
			svToken = "";
		}
	} else svToken = pinAr.token;
	if (pinAr.role != "admin") {
		var admin = confirm("Make this user an adminstrator?");
		if ( admin ) {
			svrole = "admin";
		} else {
			svrole = "";
		}
	} else svrole = "user";
	saveToken(svToken, pinAr.customer, svrole, 0, 0, 0);
	adminmenu("pendingcusts");
      }
    };
    xmlhttp.open("GET", "confuser.php?q=" + bookid, true);
    xmlhttp.send();

} // else

} // function

function acceptchg(bookid) {

var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {

        chgAr = JSON.parse(this.responseText);
	if (chgAr.action == "cancelled" ) {
		if (chgAr.invoice == "" ) {
			alert("Booking " + chgAr.booking + "has been cancelled, no invoice exists");
		} else {
			alert("Booking " + chgAr.booking + "has been cancelled, Log on to Paypal to cancel the invoice");
		}
	} else {
		sms = confirm("Pincode " + chgAr.pin + " is assigned to booking: " + chgAr.booking + ".  Resend Pincode to "  + chgAr.customer + " via SMS?");
		if (sms) {
			sendpin(chgAr.pin);
		}
		if (chgAr.token == "" || chgAr.token == "undefine" ) {
			var token = prompt("User does not have a access token. Allocate token to user?", "Touch token on keypad");
			if ( token && token.length == 8 ) {
				svToken = token;
			} else {
				svToken = "";
			}
		} else svToken = chgAr.token;

		var chargeable = confirm("Make this a chargeable booking");
		if ( chgAr.charge == 1 ) {
			svcharge = 1;
			var deposit = prompt("Amount of deposit to be added to invoice","100.00");
			if (deposit > 0) {
				svdeposit = deposit;
			} else {
				svdeposit=0;
			}
		} else {
			svcharge=0;
			svdeposit=0;
		}

		saveToken(chgAr.token, chgAr.customer, "", svdeposit, bookid, svcharge);

		if (chgAr.invoice == "" ) {
			var confinvoice = confirm("Not yet invoiced, send invoice for this booking?");
		        if ( invoice ) {
		                invoiceNumber = createinvoice(pinAr["email"], bookid);
			}
		} else {
			 alert("Booking " + chgAr.booking + " has been changed, Log on to Paypal to amend the invoice");
		}
	} //else
	adminmenu("pendingchgs");
     }
    };
    xmlhttp.open("GET", "confchanges.php?q=" + bookid, true);
    xmlhttp.send();

} // function

function saveToken(token, name, role, deposit, bookingref, chargeable) {

        var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
              if (this.readyState == 4 && this.status == 200) {
		msgAr = JSON.parse(this.responseText);
//                alert(msgAr.msg);
              }
            };
            xmlhttp.open("GET", "savetoken.php?token=" + token + "&name=" + name + "&role=" + role + "&deposit=" +deposit + "&bookingref=" + bookingref + "&chargeable=" + chargeable, true);
            xmlhttp.send();
} // function

function sendpin(pin) {

	var xmlhttp = new XMLHttpRequest();
	    xmlhttp.onreadystatechange = function() {
	      if (this.readyState == 4 && this.status == 200) {
//	        document.getElementById("displace").innerHTML = this.responseText;
	      }
	    };
	    xmlhttp.open("GET", "sendsms.php?pin=" + pin, true);
	    xmlhttp.send();
} // function
</script>

<script>
function reject(bookid) {

// set booking to Rejected, 

var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
           adminmenu("pendingbooks");
	
      }
    };

    xmlhttp.open("GET", "rejbooking.php?q=" + bookid, true);
    xmlhttp.send();


}
</script>

<script>
function rejectchg(bookid) {

// set booking to Rejected, 

var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
           adminmenu("pendingchgs");
	
      }
    };

    xmlhttp.open("GET", "rejchange.php?q=" + bookid, true);
    xmlhttp.send();
}
</script>

<script>
function rejectcust(email) {

// set customer to Rejected, 

var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
           adminmenu("pendingcusts");
	
      }
    };

    xmlhttp.open("GET", "rejcust.php?q=" + email, true);
    xmlhttp.send();
}
</script>

<script>
function createinvoice(email,bookid) {

var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
//           responseAr= JSON.parse(this.responseText);
  	 invoiceNumber = this.responseText;
/*	 var submitinv =  confirm("Send invoice to hirer (PayPal Invoice Number is " + invoiceNumber + ")?");
         if (submitinv)  {
         	submitinvoice(invoiceNumber);
         } else {
         	alert("logon to Paypal to manually review and send invoice");
	}*/
	return this.response;
//invoiceId.substring(invoiceId.length-24, invoiceId.length);
      }
    };

    xmlhttp.open("GET", "createppinv.php?customerEmail=" + email + "&bookingParentId=" + bookid, true);
    xmlhttp.send();



}
</script>

<script>
function submitinvoice(invoiceId) {

var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
           
	return "Inv sent"; //invoicestatus;
      }
    };

    xmlhttp.open("GET", "submitppinv.php?invoice=" + invoiceId, true);
    xmlhttp.send();

}
</script>

<script>
function invmenu(listtype, refresh) {


    sessionStorage.setItem("invlisttype", listtype);
    document.getElementById("mainframe").hidden = true;
    document.getElementById("displace").hidden = true;
    document.getElementById("invpages").hidden = false;

    var reltypes = {self:"View Invoice",send:"Send Invoice",replace:"Amend Invoice",delete:"Delete Invoice", record_payment:"Record Payment", cancel:"Cancel Invoice",refund:"Make Refund",remind:"Send Reminder",qr_code:"Generate QR Code",record_refund:"Record Refund" };
    var txt="", xmlhttp, i;
    if (!refresh)  sessionStorage.setItem("page", 1);

    txt='<br><h3>Invoices</h3><br><table border="1"><tr><th>Status</th><th>Name</th><th>Booking<br>Name</th><th>Invoice<br>Number</th><th>Invoice<br>Date</th><th>Invoice<br>Amount</th><th>Amount<br> Outstanding</th><th>Actions</th></tr>';

	xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
	  if (this.readyState == 4 && this.status == 200) {
	    invoiceAr = JSON.parse(this.responseText);
	    number_invoices = 50 // Object.keys(invoiceAr.items).length;

	    for (i=0; i < number_invoices; i++) {

		if ("name" in invoiceAr.items[i].primary_recipients[0].billing_info) {
//			console.log( i + " " + invoiceAr.items[i].detail.invoice_number);
			writename = invoiceAr.items[i].primary_recipients[0].billing_info.name.full_name;
		} else {
			writename = invoiceAr.items[i].primary_recipients[0].billing_info.email_address;
		}

		if ( listtype == "listallinvs")	writetableentry();
		if ( listtype == "draftinvoices" && invoiceAr.items[i].status== "DRAFT" )	writetableentry();
		if ( listtype == "unpaidinvoices" &&  invoiceAr.items[i].due_amount.value >0 ) writetableentry();
		if ( listtype == "oldinvoices" &&  (date().getTime -  dateI(invoiceAr.items[i].detail.invoice_date).getTime)/(1000*3600*24)>30  )  writetableentry();


//		alert(!("name" in invoiceAr.items[i].primary_recipients[0].billing_info));

		function writetableentry(){
	      		txt += "<tr>"
			+'<td style="padding-left:10">' + invoiceAr.items[i].status + "</td>"
			+'<td style="padding-left:10">' + writename  + "</td>"
			+'<td style="padding-left:10">' + invoiceAr.items[i].detail.reference + "</td>"
			+'<td style="text-align:center">' + invoiceAr.items[i].detail.invoice_number + "</td>"
//                        +'<td style="text-align:center"><a href="#invModal" class="btn btn-default" data-toggle="modal" data-code="' +  invoiceAr.items[i].detail.invoice_number  + '">' +  invoiceAr.items[i].detail.invoice_number  + '</a></td>'
//<input class="btn btn-default btn-md" id="invdetails" value="' +  invoiceAr.items[i].detail.invoice_number  + '" onclick="showinv(this.value)">
			+'<td style="text-align:center">' + invoiceAr.items[i].detail.invoice_date + "</td>"
			+'<td style="text-align:center">£ ' + invoiceAr.items[i].amount.value + "</td>"
			+'<td style="text-align:center">£ ' + invoiceAr.items[i].due_amount.value + "</td>"
// build select to choose next action
			+"<td>" ;
		txt += '<select id="actionselect' + i + '" onchange="invoiceaction(this.value)"><option value="">Choose an action:</option>';
		for (j=0; j < Object.keys(invoiceAr.items[i].links).length; j++) {
			seloption = '<option value="' + invoiceAr.items[i].links[j].href + "^" + invoiceAr.items[i].links[j].rel + "^" + i +'">' + reltypes[invoiceAr.items[i].links[j].rel.replace("-","_")] + '</option>'
			txt += seloption;
			} // for j
		txt += "</select></td></tr>";
		} // end of writetableentry()

	    } // for i
	    txt += "</table><br>";
	    txt += '<input class="btn btn-primary" value="Close Invoices" onclick="goback()">   ';
	    txt += '<input class="btn btn-primary" value="next" onclick="changepage(this.value)">   ';
	    txt += '<input class="btn btn-primary" value="prev" onclick="changepage(this.value)">';
	    document.getElementById("invpages").innerHTML = txt;
	  } 
	}

    xmlhttp.open("GET", 'listppinvoices.php?page=' + sessionStorage.getItem("page"), true);
    xmlhttp.send();

}

function changepage(pgdir) {
invlistc = sessionStorage.getItem("invlisttype");
console.log(invlistc + " " + pgdir);
  if (pgdir=="next") { 
	curpage=sessionStorage.getItem("page")-0;
	sessionStorage.setItem("page", curpage+1);
	}

  if (pgdir=="prev" ) {
	curpage=sessionStorage.getItem("page")-0;
	if (curpage > 1) {
	sessionStorage.setItem("page", curpage-1);
	}
  }
invmenu(invlistc, sessionStorage.getItem("page"));
}
</script>

<script>
querystr=""
function invoiceaction(invaction) {

paypalRef= invaction.split("^");
document.getElementById("actionselect" + paypalRef[2]).disabled=true;

querystr= "actioninvoice.php?invoice=" + paypalRef[0] +"&action=" + paypalRef[1];

  if ( paypalRef[1] == "record-payment") {
	$("#paymentModal").modal()
	return
  }
  if ( paypalRef[1] == "record-refund") {
	 $("#refundModal").modal()
	return
  }
sendtoserver(paypalRef[1]);
}
var invoicedetail = [];

function sendtoserver(reqkind) {
var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {

	if (reqkind == "self") {
		$("#detailshere").empty();
		invoicedetail = JSON.parse(this.responseText);
		numLineItems = invoicedetail.items.length;
		$("#invModal").modal()

		$("#invcode").val(invoicedetail.detail.invoice_number)
		$("#invbookname").val(invoicedetail.detail.reference)

		var $table = $('<table id="tmptable" class="table-condensed table-bordered">');
		$("#detailshere").append($table)

		var tr = '<tr><th>Item Name</th><th>Description</th><th style="text-align:right">Quantity</th><th style="text-align:right">Amount</th><th style="text-align:right">Line Total</th></tr>'
		$("#tmptable").append(tr)

		runningtotal = 0;

		for (i = 0; i < numLineItems; i++) {
			linetotal = invoicedetail.items[i].quantity * invoicedetail.items[i].unit_amount.value;
			runningtotal = runningtotal + linetotal;
			tr = "<tr><td>"  +  invoicedetail.items[i].name + "</td>"+"<td>" + invoicedetail.items[i].description + "</td>"+"<td>" + invoicedetail.items[i].quantity + "</td>"+"<td>" + invoicedetail.items[i].unit_amount.value + "</td><td>" + linetotal + "</td><tr>"
			$("#tmptable").append(tr);
		}
		var tr = "<tr><td>Total</td><td></td><td></td><td></td><td>" + runningtotal + "</th></tr>"
		$("#tmptable").append(tr)

	}
      }
    };
    xmlhttp.open("GET", querystr, true);
    xmlhttp.send();
}
</script>

<script>
function setpaydetails() {
$("#paymentModal").modal('hide');
pmeth = document.getElementById("paymethod").value;
pamnt = document.getElementById("payamount").value;
pdate = document.getElementById("paydate").value;
querystr += "&pamount=" + pamnt + "&pmethod=" + pmeth + "&pdate=" + pdate;
sendtoserver();
}
</script>

<script>
function setrefunddetails() {
rmeth = document.getElementById("refundmethod").value;
ramnt = document.getElementById("refundamount").value;
rdate = document.getElementById("refunddate").value;
$("#refundModal").modal('hide');
querystr += "&pamount=" + ramnt + "&pmethod=" + rmeth + "&pdate=" + rdate;
sendtoserver();
}
</script>

  <!-- Modal -->
  <div class="modal" id="paymentModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:5px 10px;">
          <h4><span></span> Enter payment details</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body" style="padding:20px 25px;">
          <form role="form">
            <div class="form-group">
              <label for="paymethod"><span></span> Payment method</label>
              <select class="form-control" id="paymethod">
<?php
                $paymentmethodAr =array( "BANK_TRANSFER", "CASH", "CHECK", "CREDIT_CARD", "DEBIT_CARD", "PAYPAL", "WIRE_TRANSFER", "OTHER" );

                for ( $i=0; $i < sizeof($paymentmethodAr); $i++ ) {
                  echo '<option value="' . $paymentmethodAr[$i] .'">' . $paymentmethodAr[$i] . '</option>';
                }
                echo '</select>';
?>
            </div>
            <div class="form-group">
              <label for="paydate"><span"></span> Payment date (YYYY-MM-DD)</label>
              <input type="text" class="form-control" id="paydate" value="<?php echo date("Y-m-d") ?>" placeholder= <?php echo date("Y-m-d") ?> >
            </div>
            <div class="form-group">
              <label for="payamount"><span></span> Payment amount</label>
              <input type="text" class="form-control" id="payamount" placeholder="0.00">
            </div>
	      <input class="btn btn-primary" value="Record Payment" onclick="setpaydetails()">
          </form>
        </div>
	<div class="modal-footer">
		<button type="button" class="btn btn-info" data-dismiss="modal">Close	</button>
	</div>
        </div>
      </div>

    </div>
  </div>

  <!-- Modal -->
  <div class="modal" id="refundModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:5px 10px;">
          <h4><span></span> Enter payment details</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>

        </div>
        <div class="modal-body" style="padding:20px 25px;">
          <form role="form">
            <div class="form-group">
              <label for="refundmethod"><span></span> Payment method</label>
              <select class="form-control" id="refundmethod">
<?php
                $refundmentmethodAr =array( "BANK_TRANSFER", "CASH", "CHECK", "CREDIT_CARD", "DEBIT_CARD", "PAYPAL", "WIRE_TRANSFER", "OTHER" );

                for ( $i=0; $i < sizeof($paymentmethodAr); $i++ ) {
                  echo '<option value="' . $refundmentmethodAr[$i] .'">' . $refundmentmethodAr[$i] . '</option>';
                }
                echo '</select>';
?> 
         </div>
            <div class="form-group">
              <label for="refunddate"><span"></span> Refundment date (YYYY-MM-DD)</label>
              <input type="text" class="form-control" id="refunddate" value="<?php echo date("Y-m-d") ?>" placeholder= <?php echo date("Y-m-d") ?> >
            </div>
            <div class="form-group">
              <label for="refundamount"><span></span> Refundment amount</label>
              <input type="text" class="form-control" id="refundamount" placeholder="0.00">
            </div>
	      <input class="btn btn-primary" value="Record Refund" onclick="setrefunddetails()">
          </form>
        </div>
	<div class="modal-footer">
		<button type="button" class="btn btn-info" data-dismiss="modal">Close	</button>
	</div>
        </div>
      </div>

    </div>
  </div>


  <!-- Modal -->

<div class="modal" tabindex="-1" id="invModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="mySmallModalLabel">Invoice Details</h4>
	<span>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times</span></button>
	</span>
      </div>
      <div class="modal-body">
	Invoice Number  <input type="text" id="invcode" /><br>
	Booking Name <input type="text" id="invbookname"  />
	<div id="detailshere"></div>
      </div>
	<div class="modal-footer">
		<button type="button" class="btn btn-info" data-dismiss="modal">Close	</button>
	</div>
    </div>
  </div>
</div>

<p id="invpages"></p>


