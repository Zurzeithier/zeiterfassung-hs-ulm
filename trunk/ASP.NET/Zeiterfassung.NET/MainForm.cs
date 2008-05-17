using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Text;
using System.Windows.Forms;

namespace Zeiterfassung.NET
{
    public partial class MainForm : Form
    {
        public Action<int> delExitApp;
        public Backend backend;

        
        public MainForm()
        {
            InitializeComponent();
        }


        public void OnLoginStatusChanged(StatusCode status)
        {

            switch (status)
            {
                case StatusCode.LOGIN_SUCCESSFULL:
                    lblFullUserName.Text = backend.GetFullUsername();
                    Update1();
                    this.Show();
                break;
                case StatusCode.LOGOUT_SUCCESSFULL:
                    this.Hide();
                break;
            }
        }

        private void button4_Click(object sender, EventArgs e)
        {
            /*ZeitBuchung b = backend.GetCorrespondingZeitBuchungFor(backend.GetLastZeitBuchungForEmployee());
            if (b != null)*/
            /*backend.UpdateZeitKontoForQuarter(2, 2008);
            ZeitKonto k = backend.GetZeitKontoForQuarter(2, 2008);
            if (k != null)*/
            System.Windows.Forms.MessageBox.Show(backend.GetWorkingHoursForQuarter(2,2008).ToString());
            //textBox1.Text = (backend.QueryTest1());
        }


        private void MainForm_FormClosed(object sender, FormClosedEventArgs e)
        {
            delExitApp(0);
        }

        private void button5_Click(object sender, EventArgs e)
        {
            
            backend.DBX();
            //backend.GetTagesSymbolForDay(System.DateTime.Now);
        }

        public void Update1()
        {
            listBox1.Items.Clear();
            ZeitBuchung[] buchungen = backend.GetRecentZeitBuchungen();
            if (buchungen != null)
                foreach (ZeitBuchung b in buchungen)
                {
                    listBox1.Items.Add(b.ToString());
                }
        }
        private void MainForm_Load(object sender, EventArgs e)
        {

        }

        private void button3_Click(object sender, EventArgs e)
        {

        }

        private void btnKommen_Click(object sender, EventArgs e)
        {
            backend.NewZeitBuchungForNow(ZeitBuchung.ZBTyp.KOMMEN);
            Update1();
        }

        private void btnGehen_Click(object sender, EventArgs e)
        {
            backend.NewZeitBuchungForNow(ZeitBuchung.ZBTyp.GEHEN);
            Update1();
        }

        private void btnUpdate_Click(object sender, EventArgs e)
        {
            Update1();
        }

        private void btnLogout_Click(object sender, EventArgs e)
        {
            backend.Logout();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            foreach (string s in backend.GetUserLoginNames())
            {
                listBox1.Items.Add("UserLogin: "+s);
            }
        }
    }
}