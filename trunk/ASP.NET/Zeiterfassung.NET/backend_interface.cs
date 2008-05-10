/*
 * backend_interface.cs
 * 
 * Author: Ralph Greschner
 * Date: 04/05/2008
 * 
 * Public Interfaces for business model (back-end)
 */

using System;

namespace LibZES
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
                userId = GetUserId(username);
                userLoggedIn = true;
                status = StatusCode.LOGIN_SUCCESSFULL;
            }
            if (delLoginStatusChanged != null) delLoginStatusChanged(status);
            return status;
		}

        public StatusCode Logout()
        {
            con.Close();
            userLoggedIn = false;
            username = null;
            password = null;
            if (delLoginStatusChanged != null) delLoginStatusChanged(StatusCode.LOGOUT_SUCCESSFULL);
            return StatusCode.LOGOUT_SUCCESSFULL;

        }
        
        public int GetUserId(string p_username)
		{
        	int erg = 0;
			
			System.Data.Common.DbCommand cmd = con.CreateCommand();
			cmd.CommandText = "SELECT MId FROM dbo.Mitarbeiter WHERE LoginNamen = @UserName";
            System.Data.SqlClient.SqlParameter p;
            p = new System.Data.SqlClient.SqlParameter();
            p.DbType = System.Data.DbType.String;
            p.ParameterName = "@UserName";
            p.Value = p_username;
            cmd.Parameters.Add(p);
            
            erg = (int)cmd.ExecuteScalar();
			
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
        }

        public void LoadOptionsFromFile(string fn)
        {
            System.IO.StreamReader file = new System.IO.StreamReader(fn);
            string line = null;
            string[] line_split;
            while ((line = file.ReadLine()) != null)
            {
                if (line.Contains("="))
                {
                    line_split = line.Split("=".ToCharArray());
                    SetOption(line_split[0],line_split[1]);
                }

                
            }
            file.Close();
        }
        
        public bool IsUserLoggedIn {
			get { return userLoggedIn; }
		}
        public String GetFullUsername()
        {
            System.Data.Common.DbCommand cmd = con.CreateCommand();
            cmd.CommandText = "SELECT Vornamen,Namen FROM dbo.Mitarbeiter WHERE MId = 1";
            
            System.Data.SqlClient.SqlParameter p;
            p = new System.Data.SqlClient.SqlParameter();
            p.DbType = System.Data.DbType.Int32;
            p.ParameterName = "@MId";
            p.Value = userId;
            cmd.Parameters.Add(p);

            System.Data.Common.DbDataReader rdr = cmd.ExecuteReader();
            rdr.Read();
            string erg = "";
            erg = rdr.GetString(0) + " " + rdr.GetString(1);
            rdr.Close();

            return erg;

        }

        public void DBX()
        {
            System.Data.Common.DbCommand cmd = con.CreateCommand();
            cmd.CommandText = "DELETE FROM dbo.ZeitBuchung";
            cmd.ExecuteNonQuery();

        }

        public void NewZeitBuchungForNow(LibZES.ZeitBuchung.ZBTyp typ)
        {
            ZeitBuchung a = GetLastZeitBuchungForEmployee();
            ZeitBuchung b = new ZeitBuchung();
            b.bId = -1; // Auto Increment
            b.datum = System.DateTime.Now;
            b.koaId = 0;
            b.kstId = 0;
            b.mId = 1;
            b.typ = typ;
            switch (typ)
            {
                case ZeitBuchung.ZBTyp.GEHEN:
                    if (a == null || a.typ != ZeitBuchung.ZBTyp.KOMMEN)
                        return;
                break;
                case ZeitBuchung.ZBTyp.KOMMEN:
                    if (a != null && a.typ != ZeitBuchung.ZBTyp.GEHEN)
                        return;
                    break;

            }
            b.ToDatabase(con);
        }

        public ZeitBuchung GetLastZeitBuchungForEmployee()
        {
            System.Data.Common.DbCommand cmd = con.CreateCommand();
            cmd.CommandText = "SELECT TOP 1 BId,TypId,Datum,MId,KstId,KoaId FROM dbo.ZeitBuchung WHERE MId = @MId ORDER BY Datum DESC";

            System.Data.SqlClient.SqlParameter p;
            p = new System.Data.SqlClient.SqlParameter();
            p.DbType = System.Data.DbType.Int32;
            p.ParameterName = "@MId";
            p.Value = userId;
            cmd.Parameters.Add(p);
            
            System.Data.Common.DbDataReader rdr = cmd.ExecuteReader();
            rdr.Read();
            ZeitBuchung b = ZeitBuchung.FromReader(rdr);
            
            rdr.Close();
            return b;
        }

        public ZeitBuchung[] GetRecentZeitBuchungenForEmployee()
        {
            System.Collections.ArrayList al = new System.Collections.ArrayList();
            System.Data.Common.DbCommand cmd = con.CreateCommand();
            cmd.CommandText = "SELECT TOP 5 BId,TypId,Datum,MId,KstId,KoaId FROM dbo.ZeitBuchung WHERE MId = @MId ORDER BY Datum DESC";
            
            System.Data.SqlClient.SqlParameter p;
            p = new System.Data.SqlClient.SqlParameter();
            p.DbType = System.Data.DbType.Int32;
            p.ParameterName = "@MId";
            p.Value = userId;
            cmd.Parameters.Add(p);

            System.Data.Common.DbDataReader rdr = cmd.ExecuteReader();
            while (rdr.Read())
            {
                al.Add(ZeitBuchung.FromReader(rdr));
            }
            rdr.Close();
            ZeitBuchung[] erg = new ZeitBuchung[al.Count];
            al.CopyTo(erg);
            return erg;
        }
        
        public char GetTagesSymbolForDay(System.DateTime day)
        {
            if (day.DayOfWeek == DayOfWeek.Sunday)
                return '#';

            System.DateTime day_start = System.DateTime.Parse(day.Date.ToShortDateString());
            System.DateTime day_end = System.DateTime.Parse(day.AddDays(1).Date.ToShortDateString());
            System.Data.Common.DbCommand cmd = con.CreateCommand();
            cmd.CommandText = "SELECT TOP 1 BId,TypId,Datum,MId,KstId,KoaId FROM dbo.ZeitBuchung WHERE MId = @MId AND Datum >= @Datum1 AND Datum <= @Datum2 ORDER BY Datum DESC";
        
            System.Data.SqlClient.SqlParameter p;
            p = new System.Data.SqlClient.SqlParameter();
            p.DbType = System.Data.DbType.Int32;
            p.ParameterName = "@MId";
            p.Value = userId;
            cmd.Parameters.Add(p);

            p = new System.Data.SqlClient.SqlParameter();
            p.DbType = System.Data.DbType.Int32;
            p.ParameterName = "@Typ";
            p.Value = ZeitBuchung.ZBTypToInt(ZeitBuchung.ZBTyp.KOMMEN);
            cmd.Parameters.Add(p);

            p = new System.Data.SqlClient.SqlParameter();
            p.DbType = System.Data.DbType.Date;
            p.ParameterName = "@Datum1";
            p.Value = day_start;
            cmd.Parameters.Add(p);

            p = new System.Data.SqlClient.SqlParameter();
            p.DbType = System.Data.DbType.Date;
            p.ParameterName = "@Datum2";
            p.Value = day_end;
            cmd.Parameters.Add(p);

            System.Data.Common.DbDataReader rdr = cmd.ExecuteReader();
            rdr.Read();
            ZeitBuchung a = ZeitBuchung.FromReader(rdr);
            rdr.Close();
            if (a != null)
            {
                System.Windows.Forms.MessageBox.Show(a.ToString());
            }

            return '?';
        }

        public char[] GetTagesSymboleForMonth(int year, int month)
        {
            if (month < 1 || month > 12)
                return null;
            int anzTage = System.DateTime.DaysInMonth(year, month);
            char[] erg = new char[anzTage];
            for (int i = 0; i < anzTage; ++i)
                erg[i] = GetTagesSymbolForDay(System.DateTime.Parse(year + "-" + month + "-" + i));
            return erg;
        }
	}
}
