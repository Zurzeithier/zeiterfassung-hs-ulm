/*
 * Erstellt mit SharpDevelop.
 * Benutzer: Ralph
 * Datum: 21.04.2008
 * Zeit: 19:48
 * 
 * Sie können diese Vorlage unter Extras > Optionen > Codeerstellung > Standardheader ändern.
 */

using System;

namespace LibZES
{
	/// <summary>
	/// 
	/// </summary>
	public partial class Backend
	{
		
		public Backend()
        {
        	SetDefaultOptions();
        }
		
		public string BackendInfo
		{
			get
			{
				return "Backend MSSQL 0.1";
			}
		}
		
		
		
		
		
		public int Login(string p_username, string p_password)
        {
			if (userLoggedIn)
				Logout();

        	SetDbSettings();
            
        	if (ValidateUserCredentials(p_username, p_password) != 0)
        		return 1;
        	username = p_username;
            password = p_password;
            userid = GetUserId(username);
        	userLoggedIn = true;
            	
        	
            return 0;
		}

        public int Logout()
        {
            userLoggedIn = false;
            username = null;
            password = null;
            return 0;

        }
        
        public int GetUserId(string p_username)
		{
        	int erg = 0;
			/*
			con.Open();
			
			System.Data.Common.DbCommand cmd = con.CreateCommand();
			cmd.CommandText = "SELECT MId FROM dbo.Mitarbeiter WHERE an LIKE \"p_username\";";
			int erg = (int)cmd.ExecuteScalar();
			cmd.Dispose();
			con.Close();*/
			return erg;
		}
        
        public void SetOption(string name, string value)
        {
        	string name_key = name.ToLower();
        	options[name_key] = value;
        }
        
        public string GetOption(string name)
        {
        	string name_key = name.ToLower();
        	if (!options.Contains(name_key))
        		return "";
        	return (string)(options[name_key]);
        }
        public void SetDefaultOptions()
        {
        	options.Clear();
        	
        	SetOption("Server","www.illertech.net");
			SetOption("Database","Zeiterfassung");
			SetOption("Uid","sa");
			SetOption("Pwd","odysee2001");
			
        }
        
        public bool IsUserLoggedIn {
			get { return userLoggedIn; }
		}
      

	}
}
