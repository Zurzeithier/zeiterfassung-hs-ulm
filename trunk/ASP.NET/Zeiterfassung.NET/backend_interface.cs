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
    // Status codes for every application
    // event
    public enum StatusCode
    {
        DB_CONNECT_SUCCESSFULL,
        DB_CONNECT_FAILED,
        DB_DISCONNECT_SUCCESSFULL,
        DB_DISCONNECT_FAILED,
        LOGIN_FAILED,
        LOGIN_SUCCESSFULL,
        LOGOUT_SUCCESSFULL,
        USER_VALIDATION_FAILED,
        USER_VALIDATION_SUCCESSFULL
    }

    // Business modell for database
    // actions.
    // Encapsulates methods for
    // dedicated actions.
	public partial class Backend
    {
        #region Misc functons

        public Backend()
        {
            userId = -1;
            userLoggedIn = false;
            user = null;
            SetDefaultOptions();
        }

        public string BackendInfo
        {
            get
            {
                return "Backend MSSQL 0.1";
            }
        }

        // Method for testing purposes
        // (DataBase eXperiment)
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
            foreach (ZeitBuchung b in GetRecentZeitBuchungen())
                npcontext.DeleteObject(b);
            npcontext.Commit();
        }

        #endregion

        #region Delegates for back-end events
        public System.Action<StatusCode> delLoginStatusChanged;
        public System.Action<StatusCode> delDatabaseConnectionStateChanged;
        #endregion

        #region User access
        // Login for user.
        // The user has to login in order
        // to access all other system functionality.
        public StatusCode Login(string p_username, string p_password)
        {
            StatusCode status = StatusCode.LOGIN_FAILED;
			if (userLoggedIn)
				Logout();


        	if (ConnectDb() != StatusCode.DB_CONNECT_SUCCESSFULL)
            {
                status = StatusCode.DB_CONNECT_FAILED;
            } else
            if (ValidateUserCredentials(p_username, p_password) != StatusCode.USER_VALIDATION_SUCCESSFULL)
            {
                status = StatusCode.USER_VALIDATION_FAILED;
            }
            else
            {
                userId = user.Mid;
                userLoggedIn = true;
                status = StatusCode.LOGIN_SUCCESSFULL;
            }
            if (delLoginStatusChanged != null) delLoginStatusChanged(status);
            return status;
		}

        public StatusCode Logout()
        {
            userId = -1;
            userLoggedIn = false;
            user = null;
            DisconnectDb();
            if (delLoginStatusChanged != null) delLoginStatusChanged(StatusCode.LOGOUT_SUCCESSFULL);
            return StatusCode.LOGOUT_SUCCESSFULL;

        }
        #endregion

        #region Methods for manipulating back-end options

        // Sets an option (e.g. connection parameters)
        // in a hashtable for the back-end.
        // Parameter name and option value are saved
        // as strings.
        public void SetOption(string name, string value)
        {
        	string name_key = name.ToLower();
        	options[name_key] = value;
        }
        
        // Retrieves an option from the hashtable.
        public string GetOption(string name)
        {
        	string name_key = name.ToLower();
        	if (!options.Contains(name_key))
        		return "";
        	return (string)(options[name_key]);
        }
        // Sets default options.
        public void SetDefaultOptions()
        {
        	options.Clear();
        }
        #endregion

        #region Query methods for user status
        // Returns if user is logged in into the system
        public bool IsUserLoggedIn {
			get { return userLoggedIn; }
		}
        // Returns full name of the user
        public String GetFullUsername()
        {
            return user.Vornamen + " " + user.Namen;

        }
        #endregion

        #region Methods for transaction control and information retrieval (all that has anything to do with ZeitBuchung)

        // Creates a new Zeitbuchung for now.
        public void NewZeitBuchungForNow(ZeitBuchung.ZBTyp typ)
        {

            ZeitBuchung a = GetLastZeitBuchung();

            switch (typ)
            {
                case ZeitBuchung.ZBTyp.GEHEN:
                    if (a == null || a.Typ != ZeitBuchung.ZBTyp.KOMMEN)
                        return;
                    break;
                case ZeitBuchung.ZBTyp.KOMMEN:
                    if (a != null && a.Typ != ZeitBuchung.ZBTyp.GEHEN)
                        return;
                    break;
            }

            ZeitBuchung b = npcontext.CreateObject<ZeitBuchung>();
            b.Mid = userId;
            b.Typ = typ;
            b.Datum = System.DateTime.Now;

            npcontext.CommitObject(b);
        }

        // Gets the corresponding ZeitBuchung for the supplied parameter ZeitBuchung.
        // E.g. if the "Gehen" Buchung is supplied, the method returns
        // the corresponding "Kommen" Buchung and vice versa.
        public ZeitBuchung GetCorrespondingZeitBuchungFor(ZeitBuchung b)
        {
            if (b == null)
                return null;
            ZeitBuchung erg = null;
            string queryString = null;
            NPathQuery npathQuery = null;
            if (b.Typ == ZeitBuchung.ZBTyp.GEHEN)
            {
                queryString = "SELECT TOP 1 * FROM ZeitBuchung WHERE MId = ? AND Datum  < ? AND TypId = ? ORDER BY Datum DESC";
                npathQuery = new NPathQuery(queryString, typeof(ZeitBuchung));
                npathQuery.Parameters.Add(new QueryParameter(DbType.Int32, userId));
                npathQuery.Parameters.Add(new QueryParameter(DbType.DateTime, b.Datum));
                npathQuery.Parameters.Add(new QueryParameter(DbType.Int32, ZeitBuchung.ZBTypToInt(ZeitBuchung.ZBTyp.KOMMEN)));

            }

            if (b.Typ == ZeitBuchung.ZBTyp.KOMMEN)
            {
                queryString = "SELECT TOP 1 * FROM ZeitBuchung WHERE MId = ? AND Datum  > ? AND TypId = ? ORDER BY Datum ASC";
                npathQuery = new NPathQuery(queryString, typeof(ZeitBuchung));
                npathQuery.Parameters.Add(new QueryParameter(DbType.Int32, userId));
                npathQuery.Parameters.Add(new QueryParameter(DbType.DateTime, b.Datum));
                npathQuery.Parameters.Add(new QueryParameter(DbType.Int32, ZeitBuchung.ZBTypToInt(ZeitBuchung.ZBTyp.GEHEN)));

            }

            if (npathQuery == null)
                return null;

            try
            {
                erg = (ZeitBuchung)npcontext.GetObjectByNPath(npathQuery);
            }
            catch (Exception e)
            {
            }
            return erg;
        }

        // Gets the last registered ZeitBuchung for the user
        public ZeitBuchung GetLastZeitBuchung()
        {
            string queryString = "SELECT TOP 1 * FROM Mitarbeiter WHERE MId = ? ORDER BY Datum DESC";
            NPathQuery npathQuery = new NPathQuery(queryString, typeof(ZeitBuchung));
            npathQuery.Parameters.Add(new QueryParameter(DbType.Int32, userId));
            ZeitBuchung erg = null;
            try
            {
                erg = (ZeitBuchung)npcontext.GetObjectByNPath(npathQuery);
            }
            catch (Exception e)
            {
            }
            return erg;

        }

        // Get the last 5 ZeitBuchungen for the user
        public ZeitBuchung[] GetRecentZeitBuchungen()
        {

            string queryString = "SELECT * FROM Mitarbeiter WHERE MId = ? ORDER BY Datum DESC";
            NPathQuery npathQuery = new NPathQuery(queryString, typeof(ZeitBuchung));
            npathQuery.Parameters.Add(new QueryParameter(DbType.Int32, userId));
            ZeitBuchung[] erg = null;
            try
            {
                System.Collections.IList l = npcontext.GetObjectsByNPath(npathQuery);
                erg = new ZeitBuchung[l.Count];
                l.CopyTo(erg, 0);
            }
            catch (Exception e)
            { }
            return erg;
        }

        #endregion

        #region Methods which return the working hour in a given time period

        // Gets the working hours for the user
        // in the interval from 'start' to 'end' date
        public int GetWorkingHoursForInterval(System.DateTime start, System.DateTime end)
        {
            string queryString = "SELECT * FROM ZeitBuchung WHERE MId = ? AND Datum  >= ? AND Datum <= ? AND TypId = ? ORDER BY Datum ASC";
            NPathQuery npathQuery = new NPathQuery(queryString, typeof(ZeitBuchung));
            npathQuery.Parameters.Add(new QueryParameter(DbType.Int32, userId));
            npathQuery.Parameters.Add(new QueryParameter(DbType.DateTime, start));
            npathQuery.Parameters.Add(new QueryParameter(DbType.DateTime, end));
            npathQuery.Parameters.Add(new QueryParameter(DbType.Int32, ZeitBuchung.ZBTypToInt(ZeitBuchung.ZBTyp.KOMMEN)));

            System.Collections.IList l = npcontext.GetObjectsByNPath(npathQuery);

            int h = 0;

            foreach(ZeitBuchung b in l)
            {
                ZeitBuchung a = GetCorrespondingZeitBuchungFor(b);
                if (a == null)
                    continue;
                h += a.GetHourDiff(b);
            }
            return h;

        }

        // Return the working hours for a given quarter
        // of a year
        public int GetWorkingHoursForQuarter(int q, int y)
        {
            return GetWorkingHoursForInterval(GetQuarterStart(q, y), GetQuarterEnd(q, y));
        }

        #endregion

        #region Methods which manipulates the user's ZeitKontos

        // Update the ZeitKonto for a given interval,
        // e.g. recalculate the working hour count.
        public void UpdateZeitKontoForQuarter(int q, int y)
        {
            
            ZeitKonto k = null;

            string idS = String.Format("{0}|{1}|{2}", userId, q, y);
            try
            {
                k = npcontext.GetObjectById<ZeitKonto>(idS);
            }
            catch (Exception e)
            { }
            if (k == null)
            {
                k = npcontext.CreateObject<ZeitKonto>();
                k.MId = userId;
                k.Periode = q;
                k.Jahr = y;
            }

            npcontext.CommitObject(k);
        }

        // Returns the ZeitKonto for a given quarter
        public ZeitKonto GetZeitKontoForQuarter(int q, int y)
        {
            ZeitKonto k = null;

            string idS = String.Format("{0}|{1}|{2}", userId, q, y);
            try
            {
                k = npcontext.GetObjectById<ZeitKonto>(idS);
            }
            catch (Exception e)
            { }
            
            return k;
        }

        #endregion
        
	}
}
