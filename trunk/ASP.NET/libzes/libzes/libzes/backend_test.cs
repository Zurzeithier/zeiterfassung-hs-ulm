/*
 * Benutzer: Ralph Greschner
 * Datum: 23.04.2008
 * Zeit: 15:00
 * 
 * Klassenbibliothek, die über Methoden
 * die Funktionen des Zeiterfassungssystems kapselt,
 * sodass diese in allen Implementierungen identisch
 * zur Verfügung stehen
 *
 * Tests
 *
 */

using System;

namespace LibZES
{
	/// <summary>
	/// Description of backend_test.
	/// </summary>
	public partial class Backend
	{
		public void Test(int no)
        {
        	/*
        	if (ConnectDb() != 0)
        	{
        		Console.WriteLine("Verbindung DB nicht erfolgreich!");
        		return;
        	}
        	Console.WriteLine("Verbindung DB erfolgreich!"); 
 			*/
 			
 		con.Open();
        	System.Data.Common.DbCommand cmd  =  con.CreateCommand();
        	cmd.CommandText = "SELECT Namen FROM dbo.Mitarbeiter WHERE MId = 1"; 
        	Console.WriteLine((String)cmd.ExecuteScalar());
        	con.Close();
        }
	}
}
