/*
 * Erstellt mit SharpDevelop.
 * Benutzer: Ralph
 * Datum: 21.04.2008
 * Zeit: 19:05
 * 
 * Sie können diese Vorlage unter Extras > Optionen > Codeerstellung > Standardheader ändern.
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
