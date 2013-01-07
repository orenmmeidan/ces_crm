<?
class Crm extends Query
  {
    public function __construct($debug = false)
      {
        Query::__construct($debug);
      }
    
    public function prepare_tabs($curr_tab)
      {
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
            array(
                "All Leads",
                "white_tab",
                "all_leads"
            ),
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
                "AEP",
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
        if ($_SESSION['user_type'] == 1)
          {
            $tabs_arr = array_merge($tabs_arr, $admin_tabs_arr);
          }
        
        $buffer = "<ul>";
        foreach ($tabs_arr as $key => $color)
          {
            $tabs_arr[$key] = $color;
            $curr_class     = ($curr_tab == $tabs_arr[$key][2]) ? "curr_class" : "";
            $buffer .= "<li class='" . $curr_class . " " . $tabs_arr[$key][1] . "'><a href=?tab=" . $tabs_arr[$key][2] . ">" . $tabs_arr[$key][0] . "</a></li>";
          }
        
        $buffer .= "</ul>";
        return $buffer;
      }
    public function prepare_leads_table($tab, $view_deleted)
      {
        if ($view_deleted == '1')
          {
            $filter = "`row_status`='1'";
          }
        elseif ($view_deleted == '3')
          {
            $filter = "`row_status`='0'";
          }
        elseif ($view_deleted == '2')
          {
            $filter = "`row_status`>='0'";
          }
        switch ($tab)
        {
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
                $filter .= " AND `HotWarmCool`='hot'";
                break;
            case 'cool_leads':
                $filter .= " AND `HotWarmCool`='cool'";
                break;
            case 'dc_leads':
                $filter .= " AND `Type`='dc'";
                break;
            case 'we_leads':
                $filter .= " AND `Type`='dc'";
                break;
            case 'walkins':
                $filter .= " AND `HotWarmCool`='hot'";
                break;
            case 'se_leads':
                $filter .= " AND `HotWarmCool`='hot'";
                break;
            case 'prospects':
                $filter .= " AND `HotWarmCool`='hot'";
                break;
            case 'fu':
                $filter .= " AND `HotWarmCool`='hot'";
                break;
            case 'wel':
                $filter .= " AND `HotWarmCool`='hot'";
                break;
            case 'sel':
                $filter .= " AND `HotWarmCool`='hot'";
                break;
            case 'll':
                $filter .= "";
                break;
            case 'condo':
                $filter .= "AND `HotWarmCool`='hot'";
                break;
            case 'prospects_list':
                $filter .= " AND `HotWarmCool`='hot'";
                break;
            case 'dc':
                $filter .= " AND `HotWarmCool`='hot'";
                break;
            case 'aep':
                $filter .= " AND `Type`='aep'";
                break;
            default:
                $filter .= "";
                break;
        }
        if ($tab == "exp")
          {
            $buffer = $this->prepare_for_display_expiration($filter);
          }
        elseif ($tab == "ces_clients")
          {
            $buffer = $this->prepare_for_display_clients($filter);
          }
        
        else
          {
            $buffer = $this->prepare_for_display($filter);
          }
        
        return $buffer;
      }
    
    private function prepare_for_display($filter)
      {
        $rows             = $this->getRows(PREFIX . "leads", $filter, "", "", null, false);
        //print_r($rows);
        //echo sizeof($rows);
        $table_titles_arr = array(
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
        $buffer           = "<div id='table' class='leads'>
		<table class='cruises scrollable' >
		<thead>
		<tr>
		";
        for ($i = 0; $i < sizeof($table_titles_arr); $i++)
          {
            $buffer .= "<th>" . $table_titles_arr[$i] . "</th>";
          }
        $buffer .= "
		</tr>
		</thead>
		<tbody>
		";
        for ($j = 0; $j < sizeof($rows); $j++)
          {
            $tr_class = ($rows[$j]['row_status'] == "0") ? "deleted_row" : "";
            $buffer .= "<tr class='" . $tr_class . "'>
		";
            $buffer .= "<td><textarea name = 'CompanyName' class='size_130' id='" . $rows[$j]['id'] . "-CompanyName'>" . $rows[$j]['CompanyName'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'Description' class='size_200' id='" . $rows[$j]['id'] . "-Description'>" . $rows[$j]['Description'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'HotWarmCool' class='size_100' id='" . $rows[$j]['id'] . "-HotWarmCool'>" . $rows[$j]['HotWarmCool'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'LastContactDate' class='size_100' id='" . $rows[$j]['id'] . "-LastContactDate'>" . $rows[$j]['LastContactDate'] . "</textarea>";
            $buffer .= "<td><textarea name = 'SalesRep' class='size_150' id='" . $rows[$j]['id'] . "-SalesRep'>" . $rows[$j]['SalesRep'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'ContactName' class='size_150' id='" . $rows[$j]['id'] . "-ContactName'>" . $rows[$j]['ContactName'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'Email' class='size_150' id='" . $rows[$j]['id'] . "-Email'>" . $rows[$j]['Email'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'SecondEmail' class='size_150' id='" . $rows[$j]['id'] . "-SecondEmail'>" . $rows[$j]['SecondEmail'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'Phone' class='size_150' id='" . $rows[$j]['id'] . "-Phone'>" . $rows[$j]['Phone'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'FaxSecondNumberExt' class='size_150' id='" . $rows[$j]['id'] . "-FaxSecondNumberExt'>" . $rows[$j]['FaxSecondNumberExt'] . "</textarea></td>";
            if ($rows[$j]['row_status'] == "1")
              {
                $buffer .= "<td><input type='submit' class='delete' id='" . $rows[$j]['id'] . "-delete' value='Delete' / ></td>";
              }
            else
              {
                $buffer .= "<td><input type='submit' class='restore' id='" . $rows[$j]['id'] . "-delete' value='Restore' / ></td>";
              }
            $buffer .= "
		</tr>";
          }
        $buffer .= "</tbody></table>
		</div>";
        // new row
        $buffer .= "<div id='new_row'>
		<form id='new_row_form'>
		<table class='cruises scrollable' >
		<body>
		<tr>
		";
        $buffer .= "<td><textarea name = 'CompanyName' class='size_130' id='-CompanyName'></textarea></td>";
        $buffer .= "<td><textarea name = 'Description' class='size_200' id='-Description'></textarea></td>";
        $buffer .= "<td><textarea name = 'HotWarmCool' class='size_100' id='-HotWarmCool'></textarea></td>";
        $buffer .= "<td><textarea name = 'LastContactDate' class='size_100' id='-LastContactDate'></textarea>";
        $buffer .= "<td><textarea name = 'SalesRep' class='size_150' id='-SalesRep'></textarea></td>";
        $buffer .= "<td><textarea name = 'ContactName' class='size_150' id='-ContactName'></textarea></td>";
        $buffer .= "<td><textarea name = 'Email' class='size_150' id='-Email'></textarea></td>";
        $buffer .= "<td><textarea name = 'SecondEmail' class='size_150' id='-SecondEmail'></textarea></td>";
        $buffer .= "<td><textarea name = 'Phone' class='size_150' id='-Phone'></textarea></td>";
        $buffer .= "<td><textarea name = 'FaxSecondNumberExt' class='size_150' id='-FaxSecondNumberExt'></textarea></td>";
        $buffer .= "<td><input type='submit' name='table' value='Insert'  id='add_row' / ></td>";
        $buffer .= "
		</tr></tbody></table></form></div>";
        return $buffer;
      }
    
    private function prepare_for_display_expiration($filter)
      {
        $rows             = $this->getRows(PREFIX . "expiration", $filter, "", "", null, false);
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
        $buffer           = "<div id='table'  class='expiration'>
		<table class='cruises scrollable'>
		<thead>
		<tr>
		";
        for ($i = 0; $i < sizeof($table_titles_arr); $i++)
          {
            $buffer .= "<th>" . $table_titles_arr[$i] . "</th>";
          }
        $buffer .= "
		</tr>
		</thead>
		<tbody>
		";
        for ($j = 0; $j < sizeof($rows); $j++)
          {
            $tr_class = ($rows[$j]['row_status'] == "0") ? "deleted_row" : "";
            $buffer .= "<tr class='" . $tr_class . "'>
		";
            
            $buffer .= "<td><textarea name = 'renewal' class='size_130' id='" . $rows[$j]['id'] . "-renewal'>" . $rows[$j]['renewal'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'description' class='size_200' id='" . $rows[$j]['id'] . "-description'>" . $rows[$j]['description'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'contract_start_date' class='size_100' id='" . $rows[$j]['id'] . "-contract_start_date'>" . $rows[$j]['contract_start_date'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'contract_end_date' class='size_100' id='" . $rows[$j]['id'] . "-contract_end_date'>" . $rows[$j]['contract_end_date'] . "</textarea>";
            $buffer .= "<td><textarea name = 'usage' class='size_150' id='" . $rows[$j]['id'] . "-usage'>" . $rows[$j]['usage'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'mato_contact' class='size_150' id='" . $rows[$j]['id'] . "-mato_contact'>" . $rows[$j]['mato_contact'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'comission' class='size_150' id='" . $rows[$j]['id'] . "-comission'>" . $rows[$j]['comission'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'supplier' class='size_150' id='" . $rows[$j]['id'] . "-supplier'>" . $rows[$j]['supplier'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'contact' class='size_150' id='" . $rows[$j]['id'] . "-contact'>" . $rows[$j]['contact'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'email' class='size_150' id='" . $rows[$j]['id'] . "-email'>" . $rows[$j]['email'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'phone' class='size_150' id='" . $rows[$j]['id'] . "-phone'>" . $rows[$j]['phone'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'state' class='size_150' id='" . $rows[$j]['id'] . "-state'>" . $rows[$j]['state'] . "</textarea></td>";
            if ($rows[$j]['row_status'] == "1")
              {
                $buffer .= "<td><input type='submit' class='delete' id='" . $rows[$j]['id'] . "-delete' value='Delete' / ></td>";
              }
            else
              {
                $buffer .= "<td><input type='submit' class='restore' id='" . $rows[$j]['id'] . "-delete' value='Restore' / ></td>";
              }
            
            $buffer .= "
		</tr>";
          }
        $buffer .= "</tbody></table>
		</div>";
        // new row
        $buffer .= "<div id='new_row'>
		<form id='new_row_form'>
		<table class='cruises scrollable' >
		<body>
		<tr>
		";
        $buffer .= "<td><textarea name = 'renewal' class='size_130' id='renewal'></textarea></td>";
        $buffer .= "<td><textarea name = 'description' class='size_200' id='description'></textarea></td>";
        $buffer .= "<td><textarea name = 'contract_start_date' class='size_100' id='contract_start_date'></textarea></td>";
        $buffer .= "<td><textarea name = 'contract_end_date' class='size_100' id='contract_end_date'></textarea>";
        $buffer .= "<td><textarea name = 'usage' class='size_150' id='usage'></textarea></td>";
        $buffer .= "<td><textarea name = 'mato_contact' class='size_150' id='mato_contact'></textarea></td>";
        $buffer .= "<td><textarea name = 'comission' class='size_150' id='comission'></textarea></td>";
        $buffer .= "<td><textarea name = 'supplier' class='size_150' id='supplier'></textarea></td>";
        $buffer .= "<td><textarea name = 'contact' class='size_150' id='contact'></textarea></td>";
        $buffer .= "<td><textarea name = 'email' class='size_150' id='email'></textarea></td>";
        $buffer .= "<td><textarea name = 'phone' class='size_150' id='phone'></textarea></td>";
        $buffer .= "<td><textarea name = 'state' class='size_150' id='state'></textarea></td>";
        $buffer .= "<td><input type='submit' name='table' value='Insert'  id='add_row' / ></td>";
        $buffer .= "
		</tr></tbody></table></form></div>";
        return $buffer;
      }
    private function prepare_for_display_clients($filter)
      {
        $rows             = $this->getRows(PREFIX . "clients", $filter, "", "", null, false);
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
        $buffer           = "<div id='table'  class='clients'>
		<table class='cruises scrollable'>
		<thead>
		<tr>
		";
        for ($i = 0; $i < sizeof($table_titles_arr); $i++)
          {
            $buffer .= "<th>" . $table_titles_arr[$i] . "</th>";
          }
        $buffer .= "
		</tr>
		</thead>
		<tbody>
		";
        for ($j = 0; $j < sizeof($rows); $j++)
          {
            $tr_class = ($rows[$j]['row_status'] == "0") ? "deleted_row" : "";
            $buffer .= "<tr class='" . $tr_class . "'>
		";
            
            $buffer .= "<td><textarea name = 'company' class='size_130' id='" . $rows[$j]['id'] . "-company'>" . $rows[$j]['company'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'notes' class='size_200' id='" . $rows[$j]['id'] . "-notes'>" . $rows[$j]['notes'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'start_date' class='size_100' id='" . $rows[$j]['id'] . "-start_date'>" . $rows[$j]['start_date'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'expiration_date' class='size_100' id='" . $rows[$j]['id'] . "-expiration_date'>" . $rows[$j]['expiration_date'] . "</textarea>";
            $buffer .= "<td><textarea name = 'KWH' class='size_150' id='" . $rows[$j]['id'] . "-KWH'>" . $rows[$j]['KWH'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'mills_rate' class='size_150' id='" . $rows[$j]['id'] . "-mills_rate'>" . $rows[$j]['mills_rate'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'ces_cut' class='size_150' id='" . $rows[$j]['id'] . "-ces_cut'>" . $rows[$j]['ces_cut'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'broker_cut' class='size_150' id='" . $rows[$j]['id'] . "-broker_cut'>" . $rows[$j]['broker_cut'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'supplier' class='size_150' id='" . $rows[$j]['id'] . "-supplier'>" . $rows[$j]['supplier'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'contact' class='size_150' id='" . $rows[$j]['id'] . "-contact'>" . $rows[$j]['contact'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'email' class='size_150' id='" . $rows[$j]['id'] . "-email'>" . $rows[$j]['email'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'phone' class='size_150' id='" . $rows[$j]['id'] . "-phone'>" . $rows[$j]['phone'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'state' class='size_150' id='" . $rows[$j]['id'] . "-state'>" . $rows[$j]['state'] . "</textarea></td>";
            if ($rows[$j]['row_status'] == "1")
              {
                $buffer .= "<td><input type='submit' class='delete' id='" . $rows[$j]['id'] . "-delete' value='Delete' / ></td>";
              }
            else
              {
                $buffer .= "<td><input type='submit' class='restore' id='" . $rows[$j]['id'] . "-delete' value='Restore' / ></td>";
              }
            
            $buffer .= "
		</tr>";
          }
        $buffer .= "</tbody></table>
		</div>";
        // new row
        $buffer .= "<div id='new_row'>
		<form id='new_row_form'>
		<table class='cruises scrollable' >
		<body>
		<tr>
		";
        $buffer .= "<td><textarea name = 'company' class='size_130' id='company'></textarea></td>";
        $buffer .= "<td><textarea name = 'notes' class='size_200' id='notes'></textarea></td>";
        $buffer .= "<td><textarea name = 'start_date' class='size_100' id='start_date'></textarea></td>";
        $buffer .= "<td><textarea name = 'expiration_date' class='size_100' id='expiration_date'></textarea>";
        $buffer .= "<td><textarea name = 'KWH' class='size_150' id='KWH'></textarea></td>";
        $buffer .= "<td><textarea name = 'mills_rate' class='size_150' id='mills_rate'></textarea></td>";
        $buffer .= "<td><textarea name = 'ces_cut' class='size_150' id='ces_cut'></textarea></td>";
        $buffer .= "<td><textarea name = 'broker_cut' class='size_150' id='broker_cut'></textarea></td>";
        $buffer .= "<td><textarea name = 'supplier' class='size_150' id='supplier'></textarea></td>";
        $buffer .= "<td><textarea name = 'contact' class='size_150' id='contact'></textarea></td>";
        $buffer .= "<td><textarea name = 'email' class='size_150' id='email'></textarea></td>";
        $buffer .= "<td><textarea name = 'phone' class='size_150' id='phone'></textarea></td>";
        $buffer .= "<td><textarea name = 'state' class='size_150' id='state'></textarea></td>";
        $buffer .= "<td><input type='submit' name='table' value='Insert'  id='add_row' / ></td>";
        $buffer .= "
		</tr></tbody></table></form></div>";
        
        return $buffer;
      }
    private function get_new_row_content($table_name, $id)
      {
        $rows = $this->getRow(PREFIX . $table_name, "`id`='$id'", false);
        
        if ($table_name == "leads")
          {
            $buffer .= "<tr>
			";
            $buffer .= "<td><textarea name = 'CompanyName' class='size_130' id='" . $rows['id'] . "-CompanyName'>" . $rows['CompanyName'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'Description' class='size_200' id='" . $rows['id'] . "-Description'>" . $rows['Description'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'HotWarmCool' class='size_100' id='" . $rows['id'] . "-HotWarmCool'>" . $rows['HotWarmCool'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'LastContactDate' class='size_100' id='" . $rows['id'] . "-LastContactDate'>" . $rows['LastContactDate'] . "</textarea>";
            $buffer .= "<td><textarea name = 'SalesRep' class='size_150' id='" . $rows['id'] . "-SalesRep'>" . $rows['SalesRep'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'ContactName' class='size_150' id='" . $rows['id'] . "-ContactName'>" . $rows['ContactName'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'Email' class='size_150' id='" . $rows['id'] . "-Email'>" . $rows['Email'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'SecondEmail' class='size_150' id='" . $rows['id'] . "-SecondEmail'>" . $rows['SecondEmail'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'Phone' class='size_150' id='" . $rows['id'] . "-Phone'>" . $rows['Phone'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'FaxSecondNumberExt' class='size_150' id='" . $rows['id'] . "-FaxSecondNumberExt'>" . $rows['FaxSecondNumberExt'] . "</textarea></td>";
            $buffer .= "<td><input type='submit' class='delete' id='" . $rows['id'] . "-delete' value='Delete' / ></td>";
            $buffer .= "
			</tr>";
          }
        elseif ($table_name == "expiration")
          {
            $buffer .= "<tr>
			";
            
            $buffer .= "<td><textarea name = 'renewal' class='size_130' id='" . $rows['id'] . "-renewal'>" . $rows['renewal'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'description' class='size_200' id='" . $rows['id'] . "-description'>" . $rows['description'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'contract_start_date' class='size_100' id='" . $rows['id'] . "-contract_start_date'>" . $rows['contract_start_date'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'contract_end_date' class='size_100' id='" . $rows['id'] . "-contract_end_date'>" . $rows['contract_end_date'] . "</textarea>";
            $buffer .= "<td><textarea name = 'usage' class='size_150' id='" . $rows['id'] . "-usage'>" . $rows['usage'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'mato_contact' class='size_150' id='" . $rows['id'] . "-mato_contact'>" . $rows['mato_contact'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'comission' class='size_150' id='" . $rows['id'] . "-comission'>" . $rows['comission'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'supplier' class='size_150' id='" . $rows['id'] . "-supplier'>" . $rows['supplier'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'contact' class='size_150' id='" . $rows['id'] . "-contact'>" . $rows['contact'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'email' class='size_150' id='" . $rows['id'] . "-email'>" . $rows['email'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'phone' class='size_150' id='" . $rows['id'] . "-phone'>" . $rows['phone'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'state' class='size_150' id='" . $rows['id'] . "-state'>" . $rows['state'] . "</textarea></td>";
            $buffer .= "<td><input type='submit' class='delete' id='" . $rows['id'] . "-delete' value='Delete' / ></td>";
            $buffer .= "
			</tr>";
          }
        elseif ($table_name == "clients")
          {
            $buffer .= "<tr>
			";
            
            $buffer .= "<td><textarea name = 'company' class='size_130' id='" . $rows['id'] . "-company'>" . $rows['company'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'notes' class='size_200' id='" . $rows['id'] . "-notes'>" . $rows['notes'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'start_date' class='size_100' id='" . $rows['id'] . "-start_date'>" . $rows['start_date'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'expiration_date' class='size_100' id='" . $rows['id'] . "-expiration_date'>" . $rows['expiration_date'] . "</textarea>";
            $buffer .= "<td><textarea name = 'KWH' class='size_150' id='" . $rows['id'] . "-KWH'>" . $rows['KWH'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'mills_rate' class='size_150' id='" . $rows['id'] . "-mills_rate'>" . $rows['mills_rate'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'ces_cut' class='size_150' id='" . $rows['id'] . "-ces_cut'>" . $rows['ces_cut'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'broker_cut' class='size_150' id='" . $rows['id'] . "-broker_cut'>" . $rows['broker_cut'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'supplier' class='size_150' id='" . $rows['id'] . "-supplier'>" . $rows['supplier'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'contact' class='size_150' id='" . $rows['id'] . "-contact'>" . $rows['contact'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'email' class='size_150' id='" . $rows['id'] . "-email'>" . $rows['email'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'phone' class='size_150' id='" . $rows['id'] . "-phone'>" . $rows['phone'] . "</textarea></td>";
            $buffer .= "<td><textarea name = 'state' class='size_150' id='" . $rows['id'] . "-state'>" . $rows['state'] . "</textarea></td>";
            $buffer .= "<td><input type='submit' class='delete' id='" . $rows['id'] . "-delete' value='Delete' / ></td>";
            $buffer .= "
			</tr>";
          }
        return $buffer;
      }
    public function update_leads_table($content_arr)
      {
        $table_name = end($content_arr);
        //$table_name =$table_arr[1];
        
        
        foreach ($content_arr as $key => $value)
          {
            //print_r($q_a_arr);
            //find id of row
            $id          = substr($key, 0, stripos($key, "-"));
            //find which column to update
            $column_name = substr($key, (stripos($key, "-") + 1));
            //find the value of the cell
            if (is_array($value))
              {
                $final_val = $value[(sizeof($value) - 1)];
              }
            else
              {
                $final_val = $value;
              }
            if ($key != "table")
              {
                $fields = array(
                    $column_name => $final_val
                );
                $result = $this->update(PREFIX . $table_name, $fields, "`id`='$id'", "1", true);
                
              }
            //echo $result;
          }
        return $result;
      }
    public function insert_row_to_table_table($content_arr)
      {
        $table_name = end($content_arr);
        $fields     = array();
        foreach ($content_arr as $key => $value)
          {
            if ($key != "table")
              {
                $fields[$key] = $value;
              }
            
          }
        $new_id = $this->add(PREFIX . $table_name, $fields, true);
        if ($new_id > 1)
          {
            $new_row_content = $this->get_new_row_content($table_name, $new_id);
          }
        
        $result_arr = array(
            "new_id" => $new_id,
            "table" => $table_name,
            "new_row_content" => $new_row_content
        );
        return $result_arr;
      }
    
    public function delete_row($content_arr)
      {
        $fields = array();
        foreach ($content_arr as $key => $value)
          {
            $fields[$key] = $value;
            
          }
        $table_name = $fields["table"];
        $id         = explode("-", $fields["id"]);
        $fields1    = array(
            "row_status" => 0
        );
        $result     = $this->update(PREFIX . $table_name, $fields1, "`id`='" . $id[0] . "'", "1", true);
        $result_arr = array(
            "new_id" => $fields["id"],
            "result" => $result
        );
        return $result_arr;
        
      }
    public function restore_row($content_arr)
      {
        $fields = array();
        foreach ($content_arr as $key => $value)
          {
            $fields[$key] = $value;
            
          }
        $table_name = $fields["table"];
        $id         = explode("-", $fields["id"]);
        $fields1    = array(
            "row_status" => 1
        );
        $result     = $this->update(PREFIX . $table_name, $fields1, "`id`='" . $id[0] . "'", "1", true);
        $result_arr = array(
            "new_id" => $fields["id"],
            "result" => $result
        );
        return $result_arr;
        
      }
  }
?>