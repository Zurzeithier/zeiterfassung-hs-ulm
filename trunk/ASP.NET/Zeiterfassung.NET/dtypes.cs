using System;
using System.Collections.Generic;
using System.Text;

using Puzzle.NPersist.Framework.Attributes;

namespace Zeiterfassung.NET
{
    // Represents an employee ( = system user )
    [ClassMap(Table = "Mitarbeiter")]
    public class Mitarbeiter
    {
        string m_Namen;
        [PropertyMap(Columns = "Namen")]
        public virtual string Namen
        {
            get { return m_Namen; }
            set { m_Namen = value; }
        }
        string m_Vornamen;
        [PropertyMap(Columns = "Vornamen")]
        public virtual string Vornamen
        {
            get { return m_Vornamen; }
            set { m_Vornamen = value; }
        }
        string m_LoginNamen;
        [PropertyMap(Columns = "LoginNamen")]
        public virtual string LoginNamen
        {
            get { return m_LoginNamen; }
            set { m_LoginNamen = value; }
        }
        string m_LoginPasswort;
        [PropertyMap(Columns = "LoginPasswort")]
        public virtual string LoginPasswort
        {
            get { return m_LoginPasswort; }
            set { m_LoginPasswort = value; }
        }
        System.Int32 m_Mid;
        [PropertyMap(Columns = "Mid", IsIdentity = true, IsAssignedBySource = true)]
        public virtual System.Int32 Mid
        {
            get { return m_Mid; }
            //set { m_Mid = value; }
        }



    }

    // Represents a ZeitBuchung (action on given time)
    [ClassMap(Table = "ZeitBuchung")]
    public class ZeitBuchung
    {
        // Gets the time difference in hours for the current and
        // a supplied ZeitBuchung
        public int GetHourDiff(ZeitBuchung b)
        {
            System.TimeSpan d;
            if (b.Datum > Datum)
                d = b.Datum - Datum;
            else
                d = Datum - b.Datum;
            return d.Hours;
        }

        // Type for ZeitBuchung
        public enum ZBTyp
        {
            UNBEKANNT,
            KOMMEN,
            GEHEN
        }
        // Convert ZBTyp to integer (same as column
        // value in database)
        public static int ZBTypToInt(ZBTyp typ)
        {
            return (int)typ;
        }
        // Convert ZBTyp to string
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

        // Convert int to ZBTyp
        public static ZBTyp IntToZBTyp(int no)
        {
            return (ZBTyp)no;
        }

        public ZBTyp Typ
        {
            get { return IntToZBTyp(TypId); }
            set { TypId = ZBTypToInt(value); }
        }

        private int m_Bid;

        [PropertyMap(Columns = "Bid",
        IsIdentity = true, IsAssignedBySource = true)]
        public virtual int Bid
        {
            get { return m_Bid; }
            //set { m_Bid = value; }
        }
        private System.DateTime m_Datum;


        [PropertyMap(Columns = "Datum")]
        public virtual System.DateTime Datum
        {
            get { return m_Datum; }
            set { m_Datum = value; }
        }
        private int m_TypId;

        [PropertyMap(Columns = "TypId")]
        public virtual int TypId
        {
            get { return m_TypId; }
            set { m_TypId = value;  }
        }
        private int m_Mid;

        [PropertyMap(Columns = "Mid")]
        public virtual int Mid
        {
            get { return m_Mid; }
            set { m_Mid = value; }
        }
        private int m_KstId;

        [PropertyMap(Columns = "KstId")]
        public virtual int KstId
        {
            get { return m_KstId; }
            set { m_KstId = value; }
        }
        private int m_KoaId;

        [PropertyMap(Columns = "KoaId")]
        public virtual int KoaId
        {
            get { return m_KoaId; }
            set { m_KoaId = value; }
        }
        

        public override string ToString()
        {
            return Datum.ToString() + ": " + ZBTypToString(IntToZBTyp(m_TypId));
        }
        string m_Zid;

        
        
    }

    // Represents the ZeitKonto for the employee
    // in a given year and quarter
    [ClassMap(Table = "ZeitKonto")]
    public class ZeitKonto
    {
        int m_Jahr;

        [PropertyMap(Columns = "Jahr", IsIdentity = true, IdentityIndex = 2)]
        public virtual int Jahr
        {
          get { return m_Jahr; }
          set { m_Jahr = value; }
        }

        int m_Periode;

        [PropertyMap(Columns = "Periode", IsIdentity=true, IdentityIndex=1)]
        public virtual int Periode
        {
            get { return m_Periode; }
            set { m_Periode = value; }
        }

        int m_MId;

        [PropertyMap(Columns = "MId", IsIdentity = true, IdentityIndex = 0)]
        public virtual int MId
        {
            get { return m_MId; }
            set { m_MId = value; }
        }

        int m_MinSoll;

        [PropertyMap(Columns = "MinSoll")]
        public virtual int MinSoll
        {
            get { return m_MinSoll; }
            set { m_MinSoll = value; }
        }

        int m_MinHaben;

        [PropertyMap(Columns = "MinHaben")]
        public virtual int MinHaben
        {
            get { return m_MinHaben; }
            set { m_MinHaben = value; }
        }

        int m_MinSaldo;

        [PropertyMap(Columns = "MinSaldo")]
        public virtual int MinSaldo
        {
            get { return m_MinSaldo; }
            set { m_MinSaldo = value; }
        }
        

    }
 

}
