using System;
using System.Collections.Generic;
using System.Text;

using Puzzle.NPersist.Framework.Attributes;

namespace Zeiterfassung.NET
{
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





    [ClassMap(Table = "ZeitBuchung")]
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

        
        
    }

}
