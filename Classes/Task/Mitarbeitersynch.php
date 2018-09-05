<?php
namespace TYPO3\Staffm\Task;
//require_once (t3lib_extMgm::extPath('adodb').'adodb/adodb.inc.php');

/*
 * Bei Taskerstellung auf den Namespace achten! Sonst wird der Task nicht gefunden.
 */

/**
 * Mitarbeitersynch
 * Synchronisiert fehlende Mitarbeiterdaten aus anderen DB
 *
 * @author Markus Puffer <mpuffer@parat.eu>
 */
class Mitarbeitersynch extends \TYPO3\CMS\Scheduler\Task\AbstractTask {
	public function execute() {
		//require_once (t3lib_extMgm::extPath('adodb').'adodb/adodb.inc.php');
		define('SERVER', 'ora_srv\pen83');
		define('CONID', 'PCOR83');
		define('CONNECT', 'ora_srv:1526/pen83');
		define('PORT', 1526);
		define('USER', 'system');
		define('PASS', 'manager');
		define('DB', 'pen83');
		$db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = ora_srv)(PORT = 1521)))(CONNECT_DATA=(SID=XXXX)))"; 
		
		$query = "SELECT KUNDEN_NR, AUFNAHMEDATUM FROM PPAD.PKFI WHERE KUNDEN_NR = 512";
		
		/*try {			
			$conn = oci_connect(USER, PASS, Connect);
			return TRUE;
		} catch (Exception $ex) {			
			return FALSE;
		}*/
		
		/*$dbConn = &ADONewConnection('oci8');
		$dbConn->PConnect('ora_srv', 'system', 'manager', 'bpz712');*/
		/*$conn = oci_connect(USER, PASS,'
			(DESCRIPTION =
			(ADDRESS = (PROTOCOL = TCP)(HOST = '.SERVER.')(PORT = '.PORT.'))
			(CONNECT_DATA = (SID = '.DB.')
			))
		');*/
		
		/** TESTMODUL 1 
		$conn = oci_connect(USER, PASS, Connect);
		 * 
		$stid = oci_parse($conn, $query);
		if (!$stid) {
			$e = oci_error($conn);
			echo $e['message'];
		}
		
		$r = oci_execute($stid, OCI_DEFAULT);
		if (!$r) {
			$e = oci_error($stid);
			echo $e['message'];
		}


		$nrows = oci_fetch_all($stid, $results, "0", "-1", OCI_ASSOC+OCI_FETCHSTATEMENT_BY_ROW);
		if ($nrows > 0 ) {
			foreach ($results as $row) {
				echo $row['field'];
			}
		}
		
		oci_close($conn);*/
		
		/** Test 2 */
		$server = "ora_srv/PCOR83";
		$database = "pen83";
		$user = "system";
		$pw = "manager";
		$connection_string = "DRIVER={Microsoft ODBC for Oracle};SERVER=$server;DATABASE=$database"; 
		$src = odbc_connect($connection_string, $user, $pw);
		if (!$src) {
			return FALSE;
		} else {
			$query = "SELECT KUNDEN_NR, AUFNAHMEDATUM FROM PPAD.PKFI WHERE KUNDEN_NR = 512";			
			return TRUE;
		}
		
		/** Test 3 *
		$conn = new COM("ADODB.Connection") or die("Cannot start ADO");
		$conn->Open("Provider=SQLOLEDB; Data Source=ora_srv;
		Initial Catalog=pen83; User ID=system; Password=manager");

		$rs = $conn->Execute($query);    // Recordset*/

	}
}
