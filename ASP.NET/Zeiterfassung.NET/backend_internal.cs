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
        public Mitarbeiter user; // the logged-in user                                                   
		bool userLoggedIn; // is user logged in (including user validation)
        int userId; // the user's ID
        
        System.Collections.Hashtable options = new System.Collections.Hashtable(); // contains the back-end options (saved as string-string pairs)

        private IContext npcontext; // context for NPersist Persistence Framework

        // Connects to Database, initializes NPersist context
        private StatusCode ConnectDb()
        {

            
        	string cs = "Server="+GetOption("Server")+";Database="+GetOption("Database")+";Uid="+GetOption("Uid")+";Pwd="+GetOption("Pwd")+";";

            npcontext = new Context(this.GetType().Assembly);
            npcontext.SetConnectionString(cs);
            StatusCode status = StatusCode.DB_CONNECT_SUCCESSFULL;
            if (this.delDatabaseConnectionStateChanged != null)
                delDatabaseConnectionStateChanged(status);
            return status;
        }

        // Disconnect the database connection
        // (disposal of NPersist context)
        private StatusCode DisconnectDb()
        {
            npcontext.Commit();
            npcontext.Dispose();
            npcontext = null;
            StatusCode status = StatusCode.DB_DISCONNECT_SUCCESSFULL;
            if (this.delDatabaseConnectionStateChanged != null)
                delDatabaseConnectionStateChanged(status);
            return status;
        }

        // Validates if user is authorized to login
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

        // Gets the start date of a year's quarter
        DateTime GetQuarterStart(int q, int y)
        {
            if (q < 0)
                q = -q;
            q = q % 4;
            int m = 1 + 3 * (q - 1);
            return new DateTime(y, m, 1);
        }

        // Gets the end date of a year's quarter
        DateTime GetQuarterEnd(int q, int y)
        {
            if (q < 0)
                q = -q;
            q = q % 4;
            int m = 3 * q;
            return new DateTime(y, m, System.DateTime.DaysInMonth(y, m));
        }

        



        
	}
}
