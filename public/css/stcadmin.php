<?php header("Content-type: text/css; charset: UTF-8"); ?>

<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/lib/Colours/Colours.inc.php');

$colours = new Colours\ColourScheme('stcadmin');
?>

@font-face {
    font-family: bretan;
    src: url('Bretan.otf') format('opentype');
}

html, body {
    padding: 0;
    font-family: bretan;
    background-color: <?php $colours->echoColour(1, 2); ?>;
    margin: 0 auto;
    height: 100%;
}

#header {
    padding: 10px;
    width: 80%;
    margin: 0 auto;
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
    background: <?php $colours->echoColour(2, 2); ?>;
    border: solid 5px <?php $colours->echoColour(2, 3); ?>;
    border-radius: 15px;
    box-shadow: 5px 5px 3px <?php $colours->echoColour(1, 4); ?>;
    color: <?php $colours->echoColour(2, 4); ?>;
    margin: 0 auto;
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
    max-width: 80%;
    margin: 0 auto;
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

.form_section, #testproduct, #get_sku, .form_nav, .pagebox, #login_form, .dispatcher_controls {
    background-color: <?php $colours->echoColour(3, 2); ?>;
    border: solid 5px <?php $colours->echoColour(3, 4); ?>;
    color: <?php $colours->echoColour(2, 4); ?>;
    border-radius: 15px;
    box-shadow: 5px 5px 3px <?php $colours->echoColour(1, 4); ?>;
}

.small_form_container {
    max-width: 1000px;
    margin: 0 auto;
}

.variation_table {
    /*table-layout: fixed;*/
    overflow-x: scroll;
    overflow-y: hidden;
    /*width: 100%;*/
}

table.form_section td {
    padding: 0.5em;
    padding-bottom: 0.5em;
    border-collapse: collapse;
}

.largeCheckBox {
  /* All browsers except webkit*/
  transform: scale(1.5);

  /* Webkit browsers*/
  -webkit-transform: scale(1.5);
}

.form_table_field_name {
    text-align: right;
    vertical-align: middle;
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

.small_button, .toggle_all, .set_all {
    font-size: 0.75em;
    padding: 0;
    margin: 0;
    text-align: center;
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
    text-align: left;
}

.imagebox {
    margin: 1em;
    padding: 1em;
}

#login_form {
    text-align: center;
    margin: 0 auto;
    padding: 2em;
    background: <?php $colours->echoColour(3, 2); ?>;
    display: inline-block;
    /*overflow: hidden;*/
}

#login_container {
    text-align: center;
}

#login_button {
    width: 100%;
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

a.questionmark_link {
    font-size: 0.75em;
    vertical-align: text-top;
}

.editlink {
    font-size: 0.75em;
    font-weight: normal;
    margin-left: 1em;
}

.remove_x {
    color: black;
    cursor: pointer;

}

.variation_box {
    padding: 0.5em;
    background: <?php $colours->echoColour(3, 1); ?>;
    color: <?php $colours->echoColour(2, 2); ?>;
    border-radius: 5px;
}

.varient_disabled {
    color: black;
    background: gray;
}

.description {
    border: 1px solid black;
    background: white;
    color: black;
    font-family: sans-serif;
    font-size: 12px;
    padding: 1em;
}

.disabled {
    background: #f0f0f0;
}

.hidden {
    display: none;
}

#currentImages {
    text-align: center;
}

.image_variation_type_table {
    width: 100%;
}

#item_title, #short_description, #ebay_title {
    width: 100%;
}

#shipping_table {
    background: white;
    text-align: center;

}

#shipping_table th, #shipping_table td{
    padding: 1em;
    border: solid black 1px;
}

.item_details {
    background: white;
    color: black;
    font-size: medium;
    font-weight: normal;
    padding: 1em;
}

.item_details td{
    padding-left: 1em;
}

.order_table {
    background: white;
    color: #222222;
    font-size: medium;
    font-weight: normal;
    padding: 1em;
    display: inline-block;
}

.order_table td {
    border: #888888 1px solid;
}

.item_table td {
    border: none;
}

.align_right {
    text-align: right;
}

input[type="button"]:disabled {
    background: black;
}

.nospace {
    margin: 0;
    padding: 0;
    font-size: 15px;
    border-collapse: collapse;
    border-spacing: 0;
    line-height: 0;
}

.var_table_container {
    width: 95%;
    overflow-x: scroll;
    margin-left:5em;
    overflow-y:visible;
    padding-bottom:1px;
}

.headcol {
    position:absolute;
    width:150px;
    left:0;
    top:auto;
    border-top-width:3px; /*only relevant for first row*/
    margin-top:-3px; /*compensate for top border*/
    background: <?php $colours->echoColour(3, 2); ?>;
    z-index: 10;
}

#international_shipping_table {
    font-size: medium;
}

#international_shipping_table th {
    text-align: center;
}

#international_shipping_table td {
    text-align: right;
    padding-left: 1em;
}

.dispatcher {
    text-align: center;
}

.dispatcher_controls {
    display: inline-block;
    margin: 1em;
}

.dispatcher_controls .label {
    text-align: right;
}

.dispatcher_controls .input {
    text-align: left;
}

.dispatcher_controls td {
    padding: 0.5em;
}

.order_table td {
    text-align: center;
}

.order_table th.header {
    background-color: <?php $colours->echoColour(2, 2); ?>;
    background-image: url(/images/bg.gif);
    cursor: pointer;
    font-weight: bold;
    background-repeat: no-repeat;
    background-position: center left;
    padding-left: 20px;
    border-right: 1px solid #dad9c7;
    margin-left: -1px;
    text-align: center;
}

.order_table th.headerSortDown {
    background-image: url(/images/desc.gif);
    background-color: <?php $colours->echoColour(2, 3); ?>;
}

.order_table th.headerSortUp {
    background-image: url(/images/asc.gif);
    background-color: <?php $colours->echoColour(2, 3); ?>;
}
