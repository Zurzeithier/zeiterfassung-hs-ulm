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
 * Interne Methoden
 *
 */

using System;

namespace LibZES
{
	/// <summary>
	/// Description of libzeitesys_main.
	/// </summary>
	public partial class Backend
	{
		                                                                    
		bool userLoggedIn = false;
		
		System.Data.Common.DbConnection con = new System.Data.SqlClient.SqlConnection();
        string username = null;
        string password = null;
        int userid=0;
        
        System.Collections.Hashtable options = new System.Collections.Hashtable();
        private int SetDbSettings()
        {
        	string cs = "Server="+GetOption("Server")+";Database="+GetOption("Database")+";Uid="+GetOption("Uid")+";Pwd="+GetOption("Pwd")+";";
            con.ConnectionString = cs; 
        	cs = null;
        	return 0;
        }

        
        private int ValidateUserCredentials(string p_username, string p_password)
        {
        	return 0;
        	
        }

        



        
	}
}
