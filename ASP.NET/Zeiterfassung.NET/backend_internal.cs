/*
 * backend_internal.cs
 * 
 * Author: Ralph Greschner
 * Date: 04/05/2008
 * 
 * Business model (back-end), core functionality
 */

using System;
using Puzzle.NPersist.Framework;
using Puzzle.NPersist.Framework.Querying;
using System.Data;
using System.Data.Common;

namespace Zeiterfassung.NET
{
	/// <summary>
	/// Description of libzeitesys_main.
	/// </summary>
	public partial class Backend
	{
        public Mitarbeiter user;                                                      
		bool userLoggedIn = false;
		
        string username = null;
        string password = null;
        int userId=0;
        
        System.Collections.Hashtable options = new System.Collections.Hashtable();

        private IContext npcontext;

        private StatusCode ConnectDb()
        {

            
        	string cs = "Server="+GetOption("Server")+";Database="+GetOption("Database")+";Uid="+GetOption("Uid")+";Pwd="+GetOption("Pwd")+";";

            npcontext = new Context(this.GetType().Assembly);

            npcontext.SetConnectionString(cs);
            StatusCode status = StatusCode.DB_CONNECTION_SUCCESSFULL;
            if (this.delDatabaseConnectionStateChanged != null)
                delDatabaseConnectionStateChanged(status);
            return status;
        }

        
        private StatusCode ValidateUserCredentials(string p_username, string p_password)
        {
            
	    string queryString = "SELECT TOP 1 * FROM Mitarbeiter WHERE LoginNamen = ? AND LoginPasswort = ?";
	    NPathQuery npathQuery = new NPathQuery(queryString, typeof(Mitarbeiter));
	    npathQuery.Parameters.Add(new QueryParameter(DbType.String, p_username));
    	npathQuery.Parameters.Add(new QueryParameter(DbType.String, p_password));

        user = null;
        try
        {
            user = npcontext.GetObjectByNPath<Mitarbeiter>(queryString, npathQuery.Parameters);
        }
        catch (Exception e)
        {
        }
        if (user != null)
            return StatusCode.USER_VALIDATION_SUCCESSFULL;
        else
            return StatusCode.USER_VALIDATION_FAILED;
            
        	
        	
        }

        



        
	}
}
