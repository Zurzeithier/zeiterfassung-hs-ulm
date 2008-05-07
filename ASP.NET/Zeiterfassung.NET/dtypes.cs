using System;
using System.Collections.Generic;
using System.Text;

namespace LibZES
{
    public enum ZBTyp
    {
        UNBEKANNT,
        KOMMEN,
        GEHEN
    }
    public class ZeitBuchung
    {
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
        public int bId;
        public System.DateTime datum;
        public ZBTyp typ;
        public int mId;
        public int kstId;
        public int koaId;
        public ZeitBuchung()
        {

        }

        public string ToString()
        {
            return datum.ToString() + ": " + ZBTypToString(typ);
        }

        
        public static ZeitBuchung FromReader(System.Data.Common.DbDataReader rdr)
        {
            
            if (rdr == null || !rdr.HasRows)
                return null;

            ZeitBuchung b = new ZeitBuchung();

            b.bId = rdr.GetInt32(0);

            b.typ = ZBTyp.UNBEKANNT;
            int typId = rdr.GetInt32(1);
            /*switch (typId)
            {

            }*/

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
