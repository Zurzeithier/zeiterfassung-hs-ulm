/*
 * backend_interface.cs
 * 
 * Author: Ralph Greschner
 * Date: 04/05/2008
 * 
 * Public Interfaces for business model (back-end)
 */


using System;
using Puzzle.NPersist.Framework;
using Puzzle.NPersist.Framework.Querying;
using System.Data;
using System.Data.Common;

namespace Zeiterfassung.NET
{
    public enum StatusCode
    {
        DB_CONNECTION_SUCCESSFULL,
        DB_CONNECTION_FAILED,
        LOGIN_FAILED,
        LOGIN_SUCCESSFULL,
        LOGOUT_SUCCESSFULL,
        USER_VALIDATION_FAILED,
        USER_VALIDATION_SUCCESSFULL
    }
	/// <summary>
	/// 
	/// </summary>
	public partial class Backend
	{

        public System.Action<StatusCode> delLoginStatusChanged;
        public System.Action<StatusCode> delDatabaseConnectionStateChanged;

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


		public StatusCode Login(string p_username, string p_password)
        {
            StatusCode status = StatusCode.LOGIN_FAILED;
			if (userLoggedIn)
				Logout();





        	if (ConnectDb() != StatusCode.DB_CONNECTION_SUCCESSFULL)
            {
                status = StatusCode.DB_CONNECTION_FAILED;
            } else
            if (ValidateUserCredentials(p_username, p_password) != StatusCode.USER_VALIDATION_SUCCESSFULL)
            {
                status = StatusCode.USER_VALIDATION_FAILED;
            }
            else
            {
                username = p_username;
                password = p_password;
                userId = user.Mid;
                userLoggedIn = true;
                status = StatusCode.LOGIN_SUCCESSFULL;
            }
            if (delLoginStatusChanged != null) delLoginStatusChanged(status);
            return status;
		}

        public StatusCode Logout()
        {
            userLoggedIn = false;
            username = null;
            password = null;
            if (delLoginStatusChanged != null) delLoginStatusChanged(StatusCode.LOGOUT_SUCCESSFULL);
            return StatusCode.LOGOUT_SUCCESSFULL;

        }
        
        public int GetUserId(string p_username)
		{
            return 0;
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
        }

        
        public bool IsUserLoggedIn {
			get { return userLoggedIn; }
		}
        public String GetFullUsername()
        {
            return user.Vornamen + " " + user.Namen;

        }

        public void DBX()
        {
            /*
            Mitarbeiter u1 = npcontext.CreateObject<Mitarbeiter>();
            u1.LoginNamen = "RAGRE";
            u1.Namen = "Greschner";
            u1.Vornamen = "Ralph";
            u1.LoginPasswort = "";
            npcontext.CommitObject(u1);
             * */

        }

        public void NewZeitBuchungForNow(ZeitBuchung.ZBTyp typ)
        {
            ZeitBuchung b = npcontext.CreateObject<ZeitBuchung>();
            b.Mid = userId;
            b.TypId = ZeitBuchung.ZBTypToInt(typ);
            b.Datum = System.DateTime.Now;
            System.Windows.Forms.MessageBox.Show(b.Bid.ToString()+": "+b.ToString());
            npcontext.CommitObject(b);
        }

        public ZeitBuchung GetLastZeitBuchungForEmployee()
        {
            string queryString = "SELECT TOP 1 * FROM Mitarbeiter WHERE MId = ? ORDER BY Datum DESC";
            NPathQuery npathQuery = new NPathQuery(queryString, typeof(ZeitBuchung));
            npathQuery.Parameters.Add(new QueryParameter(DbType.Int32, userId));

            return (ZeitBuchung)npcontext.GetObjectByNPath(npathQuery);

        }

        public ZeitBuchung[] GetRecentZeitBuchungenForEmployee()
        {

            string queryString = "SELECT TOP 5 * FROM Mitarbeiter WHERE MId = ? ORDER BY Datum DESC";
	        NPathQuery npathQuery = new NPathQuery(queryString, typeof(ZeitBuchung));
	        npathQuery.Parameters.Add(new QueryParameter(DbType.Int32, userId));

            System.Collections.IList l = npcontext.GetObjectsByNPath(npathQuery);
            ZeitBuchung[] erg = new ZeitBuchung[l.Count];
            l.CopyTo(erg, 0) ;
            return erg; 
        }
        
        public char GetTagesSymbolForDay(System.DateTime day)
        {
            return '?';
        }

        public char[] GetTagesSymboleForMonth(int year, int month)
        {
            return null;
        }
	}
}
