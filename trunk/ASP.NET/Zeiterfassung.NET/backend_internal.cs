/*
 * backend_internal.cs
 * 
 * Author: Ralph Greschner
 * Date: 04/05/2008
 * 
 * Business model (back-end), core functionality
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
        int userId=0;
        
        System.Collections.Hashtable options = new System.Collections.Hashtable();
        private StatusCode ConnectDb()
        {
            if (con.State == System.Data.ConnectionState.Open)
                con.Close();
        	string cs = "Server="+GetOption("Server")+";Database="+GetOption("Database")+";Uid="+GetOption("Uid")+";Pwd="+GetOption("Pwd")+";";
            con.ConnectionString = cs;
            StatusCode status = StatusCode.DB_CONNECTION_FAILED;
            try
            {
                con.Open();
                status = StatusCode.DB_CONNECTION_SUCCESSFULL;
            }
            catch (Exception e)
            {
                
            }
            if (this.delDatabaseConnectionStateChanged != null)
                delDatabaseConnectionStateChanged(status);
            return status;
        }

        
        private StatusCode ValidateUserCredentials(string p_username, string p_password)
        {
            
            System.Data.Common.DbCommand cmd = con.CreateCommand(); 
            cmd.CommandText = String.Format("SELECT LoginPasswort FROM dbo.Mitarbeiter WHERE LoginNamen = '{0}';",p_username);
            string pw = (string)cmd.ExecuteScalar();
            //System.Windows.Forms.MessageBox.Show(pw);
            if (pw == p_password)
                return StatusCode.USER_VALIDATION_SUCCESSFULL;
            else
                return StatusCode.USER_VALIDATION_FAILED;
            
        	
        	
        }

        



        
	}
}
