using System;
using System.Collections.Generic;
using System.Text;

namespace LibZES
{
    
    public class ZeitBuchung
    {
        public enum ZBTyp
        {
            UNBEKANNT,
            KOMMEN,
            GEHEN
        }
        public static int ZBTypToInt(ZBTyp typ)
        {
            return (int)typ;
        }
        public static string ZBTypToString(ZBTyp typ)
        {
            switch (typ)
            {
                case ZBTyp.KOMMEN:
                    return "Kommen";
                break;
                case ZBTyp.GEHEN:
                    return "Gehen";
                break;
                default:
                    return "???";
            }
        }

        public static ZBTyp IntToZBTyp(int no)
        {
            return (ZBTyp)no;
        }

        public int bId;
        public System.DateTime datum;
        public ZBTyp typ;
        public int mId;
        public int kstId;
        public int koaId;
        /*
        public ZeitBuchung GetCorrespondingTransaction(System.Data.Common.DbConnection con)
        {
            System.Data.Common.DbCommand cmd = con.CreateCommand();
            if (typ = ZBTyp.GEHEN)
                cmd.CommandText = "SELECT * FROM dbo.ZeitBuchung WHERE Datum < @Datum";
            if (typ = ZBTyp.KOMMEN)
                cmd.CommandText = "SELECT * FROM dbo.ZeitBuchung WHERE Datum > @Datum";
            
            System.Data.SqlClient.SqlParameter p;
            p = new System.Data.SqlClient.SqlParameter();
            p.DbType = System.Data.DbType.DateTime;
            p.ParameterName = "@Datum";
            p.Value = datum;
            cmd.Parameters.Add(p);

            System.Data.Common.DbDataReader rdr = cmd.ExecuteReader();
            ZeitBuchung b = ZeitBuchung.FromReader(rdr);

            return b;
        }
         * */
        public ZeitBuchung()
        {

        }
        public void ToDatabase(System.Data.Common.DbConnection con)
        {
            System.Data.Common.DbCommand cmd = con.CreateCommand();
            if (bId == -1)
            {
                cmd.CommandText = "INSERT INTO dbo.Zeitbuchung (TypId,Datum,MId,KstId,KoaId) VALUES (@TypId,@Datum,@MId,@KstId,@KoaId)";
            }
            else
            {
                cmd.CommandText = "INSERT INTO dbo.Zeitbuchung (BId,TypId,Datum,MId,KstId,KoaId) VALUES (@BId,@TypId,@Datum,@MId,@KstId,@KoaId)";
                cmd.Parameters["@BId"].Value = bId;
                cmd.Parameters["@BId"].DbType = System.Data.DbType.Int32;
            }

            System.Data.Common.DbParameter p;

            p = new System.Data.SqlClient.SqlParameter();
            p.DbType = System.Data.DbType.Int32;
            p.ParameterName = "@TypId";
            p.Value = ZeitBuchung.ZBTypToInt(typ);
            cmd.Parameters.Add(p);

            p = new System.Data.SqlClient.SqlParameter();
            p.DbType = System.Data.DbType.DateTime;
            p.ParameterName = "@Datum";
            p.Value = datum;
            cmd.Parameters.Add(p);

            p = new System.Data.SqlClient.SqlParameter();
            p.DbType = System.Data.DbType.Int32;
            p.ParameterName = "@MId";
            p.Value = mId;
            cmd.Parameters.Add(p);

            p = new System.Data.SqlClient.SqlParameter();
            p.DbType = System.Data.DbType.Int32;
            p.ParameterName = "@KstId";
            p.Value = kstId;
            cmd.Parameters.Add(p);

            p = new System.Data.SqlClient.SqlParameter();
            p.DbType = System.Data.DbType.Int32;
            p.ParameterName = "@KoaId";
            p.Value = koaId;
            cmd.Parameters.Add(p);

            cmd.ExecuteNonQuery();
        }

        public override string ToString()
        {
            return datum.ToString() + ": " + ZBTypToString(typ);
        }

        
        public static ZeitBuchung FromReader(System.Data.Common.DbDataReader rdr)
        {
            
            if (rdr == null || !rdr.HasRows)
                return null;

            ZeitBuchung b = new ZeitBuchung();

            try
            {
                b.bId = rdr.GetInt32(0);
            }
            catch (Exception e)
            {
                return null;
            }

            b.typ = ZBTyp.UNBEKANNT;
            int typId = rdr.GetInt32(1);
            b.typ = IntToZBTyp(typId);

            b.datum = rdr.GetDateTime(2);
            b.mId = rdr.GetInt32(3);
            b.kstId = rdr.GetInt32(4);
            b.koaId = rdr.GetInt32(5);

            return b;
        }
        
    }
    public class ZeitKonto
    {
        public int jahr;
        public int periode;
        public int mId;
        public int minSoll;
        public int minHaben;
        public int minSaldo;
        
    }

}
