<?php header("Content-type: text/css; charset: UTF-8"); ?>

<?php
include('colour_scheme.php');

$colours = new ColourScheme('colours.txt');
?>

@font-face {
    font-family: bretan;
    src: url(Bretan.otf);
}

html, body {
    padding: 0;
    font-family: bretan;
    background-color: <?php $colours->echoColour(1, 2); ?>;
    width: 80%;
    margin: 0 auto;
    height: 100%;
}

#header {
    padding: 10px;
}

#wrapper {
    min-height: 100%;
    position: relative;
}

#content {
    padding: 10px;
    padding-bottom: 100px;
}

#footer {
    width: 80%;
    height: 30px;
    padding: 1em;
    position: absolute;
    bottom: 0;
    left: 0;
    background: <?php $colours->echoColour(2, 2); ?>;
    border: solid 5px <?php $colours->echoColour(2, 3); ?>;
    border-radius: 15px;
    box-shadow: 5px 5px 3px <?php $colours->echoColour(1, 4); ?>;
    color: <?php $colours->echoColour(2, 4); ?>;
    margin-bottom: 2em;
}

#footer a {
    color: <?php $colours->echoColour(2, 4); ?>;
}

.copyright {
    font-size: 0.5em;
    margin-left: 1em;
}

#nav {
    list-style-type: none;
    background: <?php $colours->echoColour(2, 2); ?>;
    border: solid 5px <?php $colours->echoColour(2, 3); ?>;
    border-radius: 15px;
    box-shadow: 5px 5px 3px <?php $colours->echoColour(1, 4); ?>;
}

#nav li {
    display: inline-block;
    font-weight: bold;
    margin: 0.25em;
    padding: 1em;    
}

#nav a {
    font-size: 1.5em;
    text-decoration: none;
    color: <?php $colours->echoColour(1, 4); ?>;        
}

#nav a:hover {
    color: <?php $colours->echoColour(1, 3); ?>;
}

.pagebox {
    padding: 1em;
    font-size: 1.25em;
}

.pagebox ul li a {
    color: <?php $colours->echoColour(1,1);?>;
    text-decoration: none;
    padding: 0.25em;
}

.pagebox ul li a:hover {
    color: <?php $colours->echoColour(2,1);?>;
}

a.no_decoration {
    text-decoration: none;
}

h1{
    color: <?php $colours->echoColour(1, 1); ?>;
}

h2, h3, h4 {
    color: <?php $colours->echoColour(2, 1); ?>;
}

h4 {
    text-decoration: underline;
}

.form_section h3 {
    text-align: left;
    color: #ddd;
}

.error {
    color: red;
    margin: 0.1em;
    padding: 0;
    /*font-weight: bold;*/
}

.required {
    text-decoration: underline;
}

#submit {
    padding: 1em;
}

.form_section, #testproduct, #get_sku, .form_nav, .pagebox, #login_form {
    background-color: <?php $colours->echoColour(3, 2); ?>;
    border: solid 5px <?php $colours->echoColour(3, 4); ?>;
    color: <?php $colours->echoColour(2, 4); ?>;
    border-radius: 15px;
    box-shadow: 5px 5px 3px <?php $colours->echoColour(1, 4); ?>;
}

table.form_section td {
    padding: 0.5em;
    padding-bottom: 0.5em;
    border-collapse: collapse;
}

input[type=checkbox] {
  /* All browsers except webkit*/
  transform: scale(1.5);

  /* Webkit browsers*/
  -webkit-transform: scale(1.5);
}

.form_table_field_name {
    text-align: right;
    vertical-align: top;
}

.form_field_table_description {
    font-size: 0.9em;
}

input[type="text"], input[type="password"], textarea, select, checkbox {
    border-radius: 4px;
    border: solid 3px <?php $colours->echoColour(2, 4); ?>;
    /*box-shadow: 5px 5px 3px <?php $colours->echoColour(3, 1); ?>;*/
}

#testproduct td {
    padding: 0.25em;
}

#testvar {
    text-align: center;
}

.small_button {
    font-size: 0.5em;
    padding: 0;
    margin: 0;
    text-align: center;
}

.small_button input {
    font-size: 1.5em;
    padding: 0;
    margin: 0;
}

#get_sku #new_sku_text, #new_sku_button {
    font-size: 1.5em;
    margin: 1em;
    padding: 0.25em;
}

#get_sku .new_sku_text {
    background: #ddd;
    border: solid 5px <?php $colours->echoColour(2, 1); ?>;
}

#hidden_tick {
    font-size: 1.5em;
    color: <?php $colours->echoColour(1, 3); ?>;
    display: none;
}

.form_nav {
    margin: 1em;
    padding: 1em;
}

.primary {
    background: <?php $colours->echoColour(2, 2); ?>;
    width: 300px;
}

.skubox {
    border: solid 3px <?php $colours->echoColour(2, 3); ?>;
    width: 350px;
    min-height: 200px;
    margin: 1em;
    padding: 1em;
    display: inline-block;
    overflow: hidden;
    vertical-align: text-top;
    background: <?php $colours->echoColour(3, 2); ?>;
}

.imagebox {
    margin: 1em;
    padding: 1em;
}

#login_form {
    margin: 2em;
    padding: 2em;
    background: <?php $colours->echoColour(3, 2); ?>;
    display: inline-block;
    overflow: hidden;
}

.working {
    position: fixed;
    left: 50%;
    top: 50%;
}

#archive li {
    list-style: none;
    padding: 0.5em;
}

#logout {
    display: inline-block;
    float: right;
    font-size: 0.75em;
    overflow: hidden;
    padding-top: 0;
    padding-bottom: 0;
}

input.error {
    border-color: red;
}

#topper {
    display: inline-block;
    width: 100%;
}

.image_row {
    overflow: hidden;
    white-space: nowrap
}

.image_row img {
    padding-left: 5px;
}

#testproduct :disabled {
    color: black;
}