<?php
class Crm extends Query {
    private $tab_param;
    private $table;
    private $the_limit;
	private $WhichTab;
    public function __construct( $debug = false ) {
        Query::__construct( $debug );
    }
    public function prepare_tabs( $curr_tab ) {
        $tabs_arr       = array(
             array(
                 "Clear Energy Clients",
                "white_tab",
                "ces_clients" 
            ),
            array(
                 "expiration",
                "white_tab",
                "exp" 
            ),
       /*     array(
                 "All Leads",
                "white_tab",
                "all_leads" 
            ),
	    * */
            array(
                 "Hot Leads",
                "white_tab",
                "hot_leads" 
            ),
            array(
                 "Cool Leads",
                "white_tab",
                "cool_leads" 
            ),
            array(
                 "DC Leads",
                "white_tab",
                "dc_leads" 
            ),
            array(
                 "World Energy Leads",
                "white_tab",
                "we_leads" 
            ),
            array(
                 "Walk ins",
                "white_tab",
                "walkins" 
            ),
            array(
                 "Solar Energy Leads",
                "white_tab",
                "se_leads" 
            ),
            array(
                 "Prospects",
                "white_tab",
                "prospects" 
            ),
            array(
                 "Follow up",
                "white_tab",
                "fu" 
            ),
             array(
                 "Lost leads",
                "white_tab",
                "lol"
            ),
            array(
                 "Hot Leads",
                "red_tab",
                "hl"
            ),
            array(
                 "World Energy Leads",
                "red_tab",
                "wel" 
            ),
            array(
                 "Solar Energy Leads",
                "red_tab",
                "sel" 
            ),
            array(
                 "Lead List",
                "red_tab",
                "ll" 
            ),
            array(
                 "Condo List",
                "red_tab",
                "condo" 
            ),
            array(
                 "Prospect List",
                "red_tab",
                "prospects_list" 
            ),
            array(
                 "DC",
                "red_tab",
                "dc" 
            ),
            array(
                 "Ohio",
                "red_tab",
                "aep" 
            ) 
        );
        $admin_tabs_arr = array(
             array(
                 "Users",
                "white_tab",
                "users" 
            ) 
        );
        if ( $_SESSION[ 'user_type' ] == 1 ) {
            $tabs_arr = array_merge( $tabs_arr, $admin_tabs_arr );
        }
        $buffer = "<ul>";
        foreach ( $tabs_arr as $key => $color ) {
            $tabs_arr[ $key ] = $color;
            $curr_class       = ( $curr_tab == $tabs_arr[ $key ][ 2 ] ) ? "curr_class" : "";
            $buffer .= "<li class='" . $curr_class . " " . $tabs_arr[ $key ][ 1 ] . "'><a href=?tab=" . $tabs_arr[ $key ][ 2 ] . ">" . $tabs_arr[ $key ][ 0 ] . "</a></li>";
        }
        $buffer .= "</ul>";
        return $buffer;
    }

    public function get_table(){
      return $this->table;
    }
    public function get_filter($tab, $view_deleted){    

        $this->tab_param=$tab;
          if ( $tab == "exp" ) $this->table='expiration';
          elseif ( $tab == "ces_clients" ) $this->table='clients';
          else $this->table='leads';
//        $this->the_limit=50;
        switch ( $tab ) {
            case 'ces_clients':
                $filter .= "";
                break;
            case 'all_leads':
                $filter .= "";
                break;
            case 'exp':
                $filter .= "";
                break;
            case 'hot_leads':
                $filter .= "`sales_person_id`='".$_SESSION['user_id']."' AND `HotWarmCool`='hot'";
                $this->WhichTab='hot';
		break;
            case 'cool_leads':
                $filter .= "`sales_person_id`='".$_SESSION['user_id']."' AND `HotWarmCool`='cool'"; 
                $this->WhichTab='cool';
                break;
            case 'dc_leads':
                $filter .= "`sales_person_id`='".$_SESSION['user_id']."' AND `Type`='dc'";
                $this->WhichTab = 'dc';
                break;
            case 'we_leads':
                $filter .= "`sales_person_id`='".$_SESSION['user_id']."' AND `Type`='World Energy leads'";
				$this->WhichTab = 'World Energy leads';
                break;
            case 'walkins':
                $filter .= "`sales_person_id`='".$_SESSION['user_id']."' AND `Type`='Walk ins'";
				$this->WhichTab = 'Walk ins';
                break;
            case 'se_leads':
                $filter .= "`sales_person_id`='".$_SESSION['user_id']."' AND `Type`='Solar Energy leads'";
				$this->WhichTab = 'Solar Energy leads';
                break;
            case 'prospects':
                $filter .= "`sales_person_id`='".$_SESSION['user_id']."' AND `Type`='Prospects'";
				$this->WhichTab = 'Prospects';
                break;
            case 'fu':
                $filter .= "`sales_person_id`='".$_SESSION['user_id']."' AND `Type`='Follow up'";
				$this->WhichTab = 'Follow up';
                break;
            case 'lol':
                $filter .= "`sales_person_id`='".$_SESSION['user_id']."' AND `Type`='lol'";
                                $this->WhichTab = 'lol';
                break;
            case 'hl':
                $filter .= "`HotWarmCool`='hot'";
                                $this->WhichTab = 'hl';
                 break;
            case 'wel':
                $filter .= "`Type`='World Energy leads'";
				$this->WhichTab = 'World Energy leads';
                break;
            case 'sel':
                $filter .= "`Type`='Solar Energy leads'";
				$this->WhichTab = 'Solar Energy leads';
                break;
            case 'll':
                $filter .= "`Type`='Lead list'";
                               $this->WhichTab = 'Lead list';
                break;
            case 'condo':
                $filter .= "`Type`='Condo list'";
				$this->WhichTab = 'Condo list';
                break;
            case 'prospects_list':
                $filter .= "`Type`='Prospects'";
				$this->WhichTab = 'Prospects';
                break;
            case 'dc':
                $filter .= "`Type`='dc'";
                $this->WhichTab = 'dc';
                break;
            case 'aep':
                $filter .= "`Type`='AEP'";
                $this->WhichTab = 'AEP';
                break;
            default:
                $filter .= "";
                break;
           }

            return $filter;
        }

        public function prepare_leads_table( $tab, $view_deleted ) {
          $filter=$this->get_filter($tab, $view_deleted);
          if ( $tab == "exp" ) {
            $buffer = $this->prepare_for_display_expiration( $filter );
          } elseif ( $tab == "ces_clients" ) {
            $buffer = $this->prepare_for_display_clients( $filter );
          } else {
            $buffer = $this->prepare_for_display( $filter,$view_deleted, $this->WhichTab );
          }
        return $buffer;
    }
    public function get_tab_choices(){
       return <<<HHH
       Move To:<br />
       <select name='moveToSelect' id='moveToSelect'>
       <option value='hot_leads'>Hot Leads</option>
       <option value='cool_leads'>Cool Leads</option>
       <option value='dc_leads'>DC Leads</option>
       <option value='we_leads'>World Energy Leads</option>
       <option value='walkins'>Walk ins</option>
       <option value='se_leads'>Solar Energy Leads</option>
       <option value='prospects'>Prospects</option>
       <option value='follow_up'>Follow up</option>
       </select>
       <input type='submit' value='Move' id='moveToButton' />
HHH;
    
    }

    public function prepare_for_display_result_set($filter,$view_deleted,$offset=null, $limit=null) {
               if ( $view_deleted == '1' ) {
            $filter_deleted = " AND `row_status`='1'";
        } elseif ( $view_deleted == '3' ) {
            $filter_deleted = " AND `row_status`='0'";
	    } elseif ( $view_deleted == '2' ) {
            $filter_deleted = " AND `row_status`>='0'";
	    }
                $filter.=$filter_deleted;

            $rows = $this->getRows( PREFIX . "leads", $filter, "id desc", $limit, $offset, false );

                /*$query = "SELECT * FROM " . PREFIX . "leads L
                LEFT JOIN " . PREFIX . "leads_tabs_lookup LTL
                ON L.id = LTL.lead_id
                LEFT JOIN " . PREFIX . "leads_tabs LT
		        ON LT.id = LTL.tab_id
                WHERE LT.tab ='$filter'
		        AND L." . $filter_deleted."
                "
                ;
            $rows =$this->run_special_query( $query, false );
           */
        $buffer='';
        if (sizeof($rows)>0) $buffer.="<tr><td colspan='11' style='padding:10px;'>" . $this->get_tab_choices() . "</td></tr>";
        for ( $j = 0; $j < sizeof( $rows ); $j++ ) {
            $tr_class = ( $rows[ $j ][ 'row_status' ] == "0" ) ? "deleted_row" : "";
            $buffer .= "<tr class='" . $tr_class . "'>
                ";
            $checkbox_name_and_id = $rows[ $j ][ 'id' ] . '-MoveTo';
            $the_id=$rows[ $j ][ 'id' ];
            $buffer .= "<td style='padding:10px;'><input type='checkbox' class='checkbox_class' name='moveTo[]' id='$checkbox_name_and_id' value='$the_id' /></td>";
            $buffer .= "<td><textarea name = 'CompanyName' class='size_130' id='" . $rows[ $j ][ 'id' ] . "-CompanyName'>" . $rows[ $j ][ 'CompanyName' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'Description' class='size_200' id='" . $rows[ $j ][ 'id' ] . "-Description'>" . $rows[ $j ][ 'Description' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'HotWarmCool' class='size_100' id='" . $rows[ $j ][ 'id' ] . "-HotWarmCool'>" . $rows[ $j ][ 'HotWarmCool' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'LastContactDate' class='size_100' id='" . $rows[ $j ][ 'id' ] . "-LastContactDate'>" . $rows[ $j ][ 'LastContactDate' ] . "</textarea>";
            $buffer .= "<td><textarea name = 'SalesRep' class='size_150' id='" . $rows[ $j ][ 'id' ] . "-SalesRep'>" . $rows[ $j ][ 'SalesRep' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'ContactName' class='size_150' id='" . $rows[ $j ][ 'id' ] . "-ContactName'>" . $rows[ $j ][ 'ContactName' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'Email' class='size_150' id='" . $rows[ $j ][ 'id' ] . "-Email'>" . $rows[ $j ][ 'Email' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'SecondEmail' class='size_150' id='" . $rows[ $j ][ 'id' ] . "-SecondEmail'>" . $rows[ $j ][ 'SecondEmail' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'Phone' class='size_150' id='" . $rows[ $j ][ 'id' ] . "-Phone'>" . $rows[ $j ][ 'Phone' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'FaxSecondNumberExt' class='size_150' id='" . $rows[ $j ][ 'id' ] . "-FaxSecondNumberExt'>" . $rows[ $j ][ 'FaxSecondNumberExt' ] . "</textarea></td>";
            if ( $rows[ $j ][ 'row_status' ] == "1" ) {
                $buffer .= "<td><input type='submit' class='delete' id='" . $rows[ $j ][ 'id' ] . "-delete' value='Delete' / ></td>";
            } else {
                $buffer .= "<td><input type='submit' class='restore' id='" . $rows[ $j ][ 'id' ] . "-delete' value='Restore' / ></td>";
            }
            $buffer .= "
                </tr>";
        }
    return $buffer;
    }

    private function prepare_for_display( $filter,$view_deleted, $WhichTab) {

        $table_titles_arr = array(
            "Move To",
            "Company Name",
            "Description",
            "Hot/Warm/Cool",
            "Last Contact Date",
            "Sales Rep",
            "contact name",
            "email",
            "Second email",
            "phone",
            "fax / second number / extension",
            "Action" 
        );


        for ( $i = 0; $i < sizeof( $table_titles_arr ); $i++ ) {
            $header_row .= "<th>" . $table_titles_arr[ $i ] . "</th>";
        }

        $new_row .= "<div id='new_row'>
                <form id='new_row_form'>
                <table class='cruises scrollable' >
                <tr>$header_row</tr>
                <tbody>
                <tr>
                ";
        $new_row .= "<td>&nbsp&nbsp;</td>";
        $new_row .= "<td><textarea name = 'CompanyName' class='size_130' id='-CompanyName'></textarea></td>";
        $new_row .= "<td><textarea name = 'Description' class='size_200' id='-Description'></textarea></td>";
        $new_row .= "<td><textarea name = 'HotWarmCool' class='size_100' id='-HotWarmCool'></textarea></td>";
        $new_row .= "<td><textarea name = 'LastContactDate' class='size_100' id='-LastContactDate'></textarea>";
        $new_row .= "<td><textarea name = 'SalesRep' class='size_150' id='-SalesRep'></textarea></td>";
        $new_row .= "<td><textarea name = 'ContactName' class='size_150' id='-ContactName'></textarea></td>";
        $new_row .= "<td><textarea name = 'Email' class='size_150' id='-Email'></textarea></td>";
        $new_row .= "<td><textarea name = 'SecondEmail' class='size_150' id='-SecondEmail'></textarea></td>";
        $new_row .= "<td><textarea name = 'Phone' class='size_150' id='-Phone'></textarea></td>";
        $new_row .= "<td><textarea name = 'FaxSecondNumberExt' class='size_150' id='-FaxSecondNumberExt'></textarea></td>";
        $new_row .= "<td><input type='submit' name='table' value='Insert'  id='add_row' / ></td>";
        $new_row .= "<input type='hidden' name='sales_person_id' value='".$_SESSION['user_id']."'  id='sales_person_id' / >";
        if ($WhichTab == 'hot' || $WhichTab=='cool')  $name_id='HotWarmCool';
        else $name_id='Type';

        $new_row .= "<input type='hidden' name='$name_id' value='".$WhichTab."'  id='$name_id' / >";
        $new_row .= "
                </tr></tbody></table></form></div>";

        $buffer = $new_row;
        $buffer      .= "<div id='table' class='leads'>
                <form method='post' action=''>
		<table class='cruises scrollable' >
		<thead>
		<tr>
                                
		";
        $buffer .= "
		</tr>
		</thead>
                <tbody>
		";
        //$the_limit=$this->the_limit;
        //$the_offset=0;
//        $buffer .= $this->prepare_for_display_result_set($filter,$view_deleted, $the_offset, $the_limit);
 $buffer .= $this->prepare_for_display_result_set($filter,$view_deleted);
        $buffer .= "</tbody></table>
		</form></div>";
        $buffer.=$this->getLoadJS();
        return $buffer;
    }

    private function getLoadJS(){
        $js="
<!--        <input type='button' id='load' value='Load 50 more' />-->
        <script>
        $(document).ready(function () {
          $('#load').live('click', function () {
             var view_deleted=$('#view_deleted').val();
             the_url = '/plugins/crm/bin/functions.php';
             var rowCount = $('#table tr').length-1;
             $.ajax({
                type: 'POST',
                url: the_url+'?action=load&offset='+rowCount+'&limit=$this->the_limit&tab=$this->tab_param&view_deleted='+view_deleted,
                success: function (data) {
                    $('#table tbody').append(data);
                    //alert(data);
                }//success
            }); //ajax
          });//click
        });//document.ready
        </script>
        ";
    return $js;
    }

    public function prepare_for_display_expiration_result_set($filter, $limit='', $offset=null){
        $rows = $this->getRows( PREFIX . "expiration", $filter, "id desc", $limit, $offset, false );
        $buffer='';
        for ( $j = 0; $j < sizeof( $rows ); $j++ ) {
            $tr_class = ( $rows[ $j ][ 'row_status' ] == "0" ) ? "deleted_row" : "";
            $buffer .= "<tr class='" . $tr_class . "'>
                ";
            $buffer .= "<td><textarea name = 'renewal' class='size_130' id='" . $rows[ $j ][ 'id' ] . "-renewal'>" . $rows[ $j ][ 'renewal' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'description' class='size_200' id='" . $rows[ $j ][ 'id' ] . "-description'>" . $rows[ $j ][ 'description' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'contract_start_date' class='size_100' id='" . $rows[ $j ][ 'id' ] . "-contract_start_date'>" . $rows[ $j ][ 'contract_start_date' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'contract_end_date' class='size_100' id='" . $rows[ $j ][ 'id' ] . "-contract_end_date'>" . $rows[ $j ][ 'contract_end_date' ] . "</textarea>";
            $buffer .= "<td><textarea name = 'usage' class='size_150' id='" . $rows[ $j ][ 'id' ] . "-usage'>" . $rows[ $j ][ 'usage' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'mato_contact' class='size_150' id='" . $rows[ $j ][ 'id' ] . "-mato_contact'>" . $rows[ $j ][ 'mato_contact' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'comission' class='size_150' id='" . $rows[ $j ][ 'id' ] . "-comission'>" . $rows[ $j ][ 'comission' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'supplier' class='size_150' id='" . $rows[ $j ][ 'id' ] . "-supplier'>" . $rows[ $j ][ 'supplier' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'contact' class='size_150' id='" . $rows[ $j ][ 'id' ] . "-contact'>" . $rows[ $j ][ 'contact' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'email' class='size_150' id='" . $rows[ $j ][ 'id' ] . "-email'>" . $rows[ $j ][ 'email' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'phone' class='size_150' id='" . $rows[ $j ][ 'id' ] . "-phone'>" . $rows[ $j ][ 'phone' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'state' class='size_150' id='" . $rows[ $j ][ 'id' ] . "-state'>" . $rows[ $j ][ 'state' ] . "</textarea></td>";
            if ( $rows[ $j ][ 'row_status' ] == "1" ) {
                $buffer .= "<td><input type='submit' class='delete' id='" . $rows[ $j ][ 'id' ] . "-delete' value='Delete' / ></td>";
            } else {
                $buffer .= "<td><input type='submit' class='restore' id='" . $rows[ $j ][ 'id' ] . "-delete' value='Restore' / ></td>";
            $buffer .= "
                </tr>";
         }
      }
    return $buffer;
    }

    private function prepare_for_display_expiration( $filter ) {
        //print_r($rows);
        //echo sizeof($rows);
        $table_titles_arr = array(
             "ON DECK FOR RENEWAL",
            "Description",
            "Contract start Date",
            "Contract end date",
            "Usage",
            "mills and time of contract",
            "commission",
            "Supplier",
            "Contact",
            "email",
            "phone",
            "state" 
        );

        for ( $i = 0; $i < sizeof( $table_titles_arr ); $i++ ) {
            $header_row .= "<th>" . $table_titles_arr[ $i ] . "</th>";
        }

        // new row
        $new_row .= "<div id='new_row'>
                <form id='new_row_form'>
                <table class='cruises scrollable' >
                <tr>$header_row</tr>
                <body>
                <tr>
                ";
        $new_row .= "<td><textarea name = 'renewal' class='size_130' id='renewal'></textarea></td>";
        $new_row .= "<td><textarea name = 'description' class='size_200' id='description'></textarea></td>";
        $new_row .= "<td><textarea name = 'contract_start_date' class='size_100' id='contract_start_date'></textarea></td>";
        $new_row .= "<td><textarea name = 'contract_end_date' class='size_100' id='contract_end_date'></textarea>";
        $new_row .= "<td><textarea name = 'usage' class='size_150' id='usage'></textarea></td>";
        $new_row .= "<td><textarea name = 'mato_contact' class='size_150' id='mato_contact'></textarea></td>";
        $new_row .= "<td><textarea name = 'comission' class='size_150' id='comission'></textarea></td>";
        $new_row .= "<td><textarea name = 'supplier' class='size_150' id='supplier'></textarea></td>";
        $new_row .= "<td><textarea name = 'contact' class='size_150' id='contact'></textarea></td>";
        $new_row .= "<td><textarea name = 'email' class='size_150' id='email'></textarea></td>";
        $new_row .= "<td><textarea name = 'phone' class='size_150' id='phone'></textarea></td>";
        $new_row .= "<td><textarea name = 'state' class='size_150' id='state'></textarea></td>";
        $new_row .= "<td><input type='submit' name='table' value='Insert'  id='add_row' / ></td>";
        $new_row .= "
                </tr></tbody></table></form></div>";

        $buffer = $new_row;
        $buffer           .= "<div id='table'  class='expiration'>
		<table class='cruises scrollable'>
		<thead>
		<tr>
		";
        $buffer .= "
		</tr>
		</thead>
		<tbody>
		";
//        $the_offset=0;
  //      $buffer.=$this->prepare_for_display_expiration_result_set($filter, $this->the_limit, $the_offset);
$buffer.=$this->prepare_for_display_expiration_result_set($filter);
        $buffer .= "</tbody></table>
		</div>";
        $buffer.=$this->getLoadJS();
        return $buffer;
    }

    public function prepare_for_display_clients_result_set($filter, $limit='', $offset=null){
        $rows             = $this->getRows( PREFIX . "clients", $filter, "id desc", $limit, $offset, false );
        $buffer='';
        for ( $j = 0; $j < sizeof( $rows ); $j++ ) {
            $tr_class = ( $rows[ $j ][ 'row_status' ] == "0" ) ? "deleted_row" : "";
            $buffer .= "<tr class='" . $tr_class . "'>
                ";
            $buffer .= "<td><textarea name = 'company' class='size_130' id='" . $rows[ $j ][ 'id' ] . "-company'>" . $rows[ $j ][ 'company' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'notes' class='size_200' id='" . $rows[ $j ][ 'id' ] . "-notes'>" . $rows[ $j ][ 'notes' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'start_date' class='size_100' id='" . $rows[ $j ][ 'id' ] . "-start_date'>" . $rows[ $j ][ 'start_date' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'expiration_date' class='size_100' id='" . $rows[ $j ][ 'id' ] . "-expiration_date'>" . $rows[ $j ][ 'expiration_date' ] . "</textarea>";
            $buffer .= "<td><textarea name = 'KWH' class='size_150' id='" . $rows[ $j ][ 'id' ] . "-KWH'>" . $rows[ $j ][ 'KWH' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'mills_rate' class='size_150' id='" . $rows[ $j ][ 'id' ] . "-mills_rate'>" . $rows[ $j ][ 'mills_rate' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'ces_cut' class='size_150' id='" . $rows[ $j ][ 'id' ] . "-ces_cut'>" . $rows[ $j ][ 'ces_cut' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'broker_cut' class='size_150' id='" . $rows[ $j ][ 'id' ] . "-broker_cut'>" . $rows[ $j ][ 'broker_cut' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'supplier' class='size_150' id='" . $rows[ $j ][ 'id' ] . "-supplier'>" . $rows[ $j ][ 'supplier' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'contact' class='size_150' id='" . $rows[ $j ][ 'id' ] . "-contact'>" . $rows[ $j ][ 'contact' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'email' class='size_150' id='" . $rows[ $j ][ 'id' ] . "-email'>" . $rows[ $j ][ 'email' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'phone' class='size_150' id='" . $rows[ $j ][ 'id' ] . "-phone'>" . $rows[ $j ][ 'phone' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'state' class='size_150' id='" . $rows[ $j ][ 'id' ] . "-state'>" . $rows[ $j ][ 'state' ] . "</textarea></td>";
            if ( $rows[ $j ][ 'row_status' ] == "1" ) {
                $buffer .= "<td><input type='submit' class='delete' id='" . $rows[ $j ][ 'id' ] . "-delete' value='Delete' / ></td>";
            } else {
                $buffer .= "<td><input type='submit' class='restore' id='" . $rows[ $j ][ 'id' ] . "-delete' value='Restore' / ></td>";
            }
            $buffer .= "
                </tr>";
        }
    return $buffer;
    }

    private function prepare_for_display_clients( $filter ) {
        //print_r($rows);
        //echo sizeof($rows);
        $table_titles_arr = array(
             "Company",
            "Notes",
            "Start Date",
            "Contract expiration",
            "KWH",
            "Mills/rate",
            "CES's Cut",
            "Total / Broker's cut",
            "Supplier",
            "Contact",
            "email",
            "phone",
            "state" 
        );

        for ( $i = 0; $i < sizeof( $table_titles_arr ); $i++ ) {
            $header_row .= "<th>" . $table_titles_arr[ $i ] . "</th>";
        }

        // new row
        $new_row .= "<div id='new_row'>
                <form id='new_row_form'>
                <table class='cruises scrollable' >
                <tr>$header_row</tr>  
                <tr>
                <body>
                <tr>
                ";
        $new_row .= "<td><textarea name = 'company' class='size_130' id='company'></textarea></td>";
        $new_row .= "<td><textarea name = 'notes' class='size_200' id='notes'></textarea></td>";
        $new_row .= "<td><textarea name = 'start_date' class='size_100' id='start_date'></textarea></td>";
        $new_row .= "<td><textarea name = 'expiration_date' class='size_100' id='expiration_date'></textarea>";
        $new_row .= "<td><textarea name = 'KWH' class='size_150' id='KWH'></textarea></td>";
        $new_row .= "<td><textarea name = 'mills_rate' class='size_150' id='mills_rate'></textarea></td>";
        $new_row .= "<td><textarea name = 'ces_cut' class='size_150' id='ces_cut'></textarea></td>";
        $new_row .= "<td><textarea name = 'broker_cut' class='size_150' id='broker_cut'></textarea></td>";
        $new_row .= "<td><textarea name = 'supplier' class='size_150' id='supplier'></textarea></td>";
        $new_row .= "<td><textarea name = 'contact' class='size_150' id='contact'></textarea></td>";
        $new_row .= "<td><textarea name = 'email' class='size_150' id='email'></textarea></td>";
        $new_row .= "<td><textarea name = 'phone' class='size_150' id='phone'></textarea></td>";
        $new_row .= "<td><textarea name = 'state' class='size_150' id='state'></textarea></td>";
        $new_row .= "<td><input type='submit' name='table' value='Insert'  id='add_row' / ></td>";
        $new_row .= "
                </tr></tbody></table></form></div>";

        $buffer = $new_row;
        $buffer           .= "<div id='table'  class='clients'>
		<table class='cruises scrollable'>
		<thead>
		<tr>
		";

        $buffer .= "
		</tr>
		</thead>
		<tbody>
		";
//        $the_offset=0;
  //      $buffer.=$this->prepare_for_display_clients_result_set($filter, $this->the_limit, $the_offset);
 $buffer.=$this->prepare_for_display_clients_result_set($filter);

        $buffer .= "</tbody></table>
		</div>";
        $buffer.=$this->getLoadJS();
        return $buffer;
    }
    private function get_new_row_content( $table_name, $id ) {
        $rows = $this->getRow( PREFIX . $table_name, "`id`='$id'", false );
        if ( $table_name == "leads" ) {
            $checkbox_name_and_id = $id . '-MoveTo';
            $the_id=$id;
            $buffer .= "<tr>";
            $buffer .= "<td style='padding:10px;'><input type='checkbox' class='checkbox_class' name='moveTo[]' id='$checkbox_name_and_id' value='$the_id' /></td>";
            $buffer .= "<td><textarea name = 'CompanyName' class='size_130' id='" . $rows[ 'id' ] . "-CompanyName'>" . $rows[ 'CompanyName' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'Description' class='size_200' id='" . $rows[ 'id' ] . "-Description'>" . $rows[ 'Description' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'HotWarmCool' class='size_100' id='" . $rows[ 'id' ] . "-HotWarmCool'>" . $rows[ 'HotWarmCool' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'LastContactDate' class='size_100' id='" . $rows[ 'id' ] . "-LastContactDate'>" . $rows[ 'LastContactDate' ] . "</textarea>";
            $buffer .= "<td><textarea name = 'SalesRep' class='size_150' id='" . $rows[ 'id' ] . "-SalesRep'>" . $rows[ 'SalesRep' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'ContactName' class='size_150' id='" . $rows[ 'id' ] . "-ContactName'>" . $rows[ 'ContactName' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'Email' class='size_150' id='" . $rows[ 'id' ] . "-Email'>" . $rows[ 'Email' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'SecondEmail' class='size_150' id='" . $rows[ 'id' ] . "-SecondEmail'>" . $rows[ 'SecondEmail' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'Phone' class='size_150' id='" . $rows[ 'id' ] . "-Phone'>" . $rows[ 'Phone' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'FaxSecondNumberExt' class='size_150' id='" . $rows[ 'id' ] . "-FaxSecondNumberExt'>" . $rows[ 'FaxSecondNumberExt' ] . "</textarea></td>";
            $buffer .= "<td><input type='submit' class='delete' id='" . $rows[ 'id' ] . "-delete' value='Delete' / ></td>";
            $buffer .= "
			</tr>";
        } elseif ( $table_name == "expiration" ) {
            $buffer .= "<tr>
			";
            $buffer .= "<td><textarea name = 'renewal' class='size_130' id='" . $rows[ 'id' ] . "-renewal'>" . $rows[ 'renewal' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'description' class='size_200' id='" . $rows[ 'id' ] . "-description'>" . $rows[ 'description' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'contract_start_date' class='size_100' id='" . $rows[ 'id' ] . "-contract_start_date'>" . $rows[ 'contract_start_date' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'contract_end_date' class='size_100' id='" . $rows[ 'id' ] . "-contract_end_date'>" . $rows[ 'contract_end_date' ] . "</textarea>";
            $buffer .= "<td><textarea name = 'usage' class='size_150' id='" . $rows[ 'id' ] . "-usage'>" . $rows[ 'usage' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'mato_contact' class='size_150' id='" . $rows[ 'id' ] . "-mato_contact'>" . $rows[ 'mato_contact' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'comission' class='size_150' id='" . $rows[ 'id' ] . "-comission'>" . $rows[ 'comission' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'supplier' class='size_150' id='" . $rows[ 'id' ] . "-supplier'>" . $rows[ 'supplier' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'contact' class='size_150' id='" . $rows[ 'id' ] . "-contact'>" . $rows[ 'contact' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'email' class='size_150' id='" . $rows[ 'id' ] . "-email'>" . $rows[ 'email' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'phone' class='size_150' id='" . $rows[ 'id' ] . "-phone'>" . $rows[ 'phone' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'state' class='size_150' id='" . $rows[ 'id' ] . "-state'>" . $rows[ 'state' ] . "</textarea></td>";
            $buffer .= "<td><input type='submit' class='delete' id='" . $rows[ 'id' ] . "-delete' value='Delete' / ></td>";
            $buffer .= "
			</tr>";
        } elseif ( $table_name == "clients" ) {
            $buffer .= "<tr>
			";
            $buffer .= "<td><textarea name = 'company' class='size_130' id='" . $rows[ 'id' ] . "-company'>" . $rows[ 'company' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'notes' class='size_200' id='" . $rows[ 'id' ] . "-notes'>" . $rows[ 'notes' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'start_date' class='size_100' id='" . $rows[ 'id' ] . "-start_date'>" . $rows[ 'start_date' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'expiration_date' class='size_100' id='" . $rows[ 'id' ] . "-expiration_date'>" . $rows[ 'expiration_date' ] . "</textarea>";
            $buffer .= "<td><textarea name = 'KWH' class='size_150' id='" . $rows[ 'id' ] . "-KWH'>" . $rows[ 'KWH' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'mills_rate' class='size_150' id='" . $rows[ 'id' ] . "-mills_rate'>" . $rows[ 'mills_rate' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'ces_cut' class='size_150' id='" . $rows[ 'id' ] . "-ces_cut'>" . $rows[ 'ces_cut' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'broker_cut' class='size_150' id='" . $rows[ 'id' ] . "-broker_cut'>" . $rows[ 'broker_cut' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'supplier' class='size_150' id='" . $rows[ 'id' ] . "-supplier'>" . $rows[ 'supplier' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'contact' class='size_150' id='" . $rows[ 'id' ] . "-contact'>" . $rows[ 'contact' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'email' class='size_150' id='" . $rows[ 'id' ] . "-email'>" . $rows[ 'email' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'phone' class='size_150' id='" . $rows[ 'id' ] . "-phone'>" . $rows[ 'phone' ] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'state' class='size_150' id='" . $rows[ 'id' ] . "-state'>" . $rows[ 'state' ] . "</textarea></td>";
            $buffer .= "<td><input type='submit' class='delete' id='" . $rows[ 'id' ] . "-delete' value='Delete' / ></td>";
            $buffer .= "
			</tr>";
        }
        return $buffer;
    }

    public function update_leads_table( $content_arr ) {
        $table_name = end( $content_arr );
        //$table_name =$table_arr[1];
        foreach ( $content_arr as $key => $value ) {
            //print_r($q_a_arr);
            //find id of row
            $id          = substr( $key, 0, stripos( $key, "-" ) );
            //find which column to update
            $column_name = substr( $key, ( stripos( $key, "-" ) + 1 ) );
            //find the value of the cell
            if ( is_array( $value ) ) {
                $final_val = $value[ ( sizeof( $value ) - 1 ) ];
            } else {
                $final_val = $value;
            }
            if ( $key != "table" ) {
                $fields = array(
                     $column_name => $final_val 
                );
                $result = $this->update( PREFIX . $table_name, $fields, "`id`='$id'", "1", true );
            }
            //echo $result;
        }
        return $result;
    }


    public function move_row_to_new_tab($tab, $id){
       $fields=array();
       switch($tab){
           case 'hot_leads':   $fields['HotWarmCool']='hot'; 
                               break;
           case 'cool_leads':  $fields['HotWarmCool']='cool';
                               break;
           case 'dc_leads':   $fields['type']='dc';
                               break;
           case 'we_leads':   $fields['type']='World Energy leads';
                               break;
           case 'walkins':   $fields['type']='Walk ins';
                               break;
           case 'se_leads':   $fields['type']='Solar Energy leads';
                               break;
           case 'prospects':   $fields['HotWarmCool']='Prospects';
                               break;
           case 'fu':   $fields['HotWarmCool']='Follow up';
                               break;
           default: break;
       }
       
       $result = $this->update( PREFIX . 'leads', $fields, "`id`='$id'", "1", true );
    }



    public function insert_row_to_table_table( $content_arr ) {
        $table_name = end( $content_arr );
        $fields     = array( );
        foreach ( $content_arr as $key => $value ) {
            if ( $key != "table" ) {
                $fields[ $key ] = $value;
            }
        }

        $new_id = $this->add( PREFIX . $table_name, $fields, true );
        if ( $new_id > 1 ) {
            $new_row_content = $this->get_new_row_content( $table_name, $new_id );
        }
        $result_arr = array(
             "new_id" => $new_id,
            "table" => $table_name,
            "new_row_content" => $new_row_content 
        );
        return $result_arr;
    }
    public function delete_row( $content_arr ) {
        $fields = array( );
        foreach ( $content_arr as $key => $value ) {
            $fields[ $key ] = $value;
        }
        $table_name = $fields[ "table" ];
        $id         = explode( "-", $fields[ "id" ] );
        $fields1    = array(
             "row_status" => 0 
        );
        $result     = $this->update( PREFIX . $table_name, $fields1, "`id`='" . $id[ 0 ] . "'", "1", true );
        $result_arr = array(
             "new_id" => $fields[ "id" ],
            "result" => $result 
        );
        return $result_arr;
    }
    public function restore_row( $content_arr ) {
        $fields = array( );
        foreach ( $content_arr as $key => $value ) {
            $fields[ $key ] = $value;
        }
        $table_name = $fields[ "table" ];
        $id         = explode( "-", $fields[ "id" ] );
        $fields1    = array(
             "row_status" => 1 
        );
        $result     = $this->update( PREFIX . $table_name, $fields1, "`id`='" . $id[ 0 ] . "'", "1", true );
        $result_arr = array(
             "new_id" => $fields[ "id" ],
            "result" => $result 
        );
        return $result_arr;
    }
}
?>