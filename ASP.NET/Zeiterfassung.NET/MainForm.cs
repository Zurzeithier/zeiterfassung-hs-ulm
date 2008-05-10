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

        private void btnQuery_Click(object sender, EventArgs e)
        {
            
        }

        private void btnLogout_Click(object sender, EventArgs e)
        {
            backend.Logout();
        }

        
        public MainForm()
        {
            InitializeComponent();
        }

        private void MainForm_Load(object sender, EventArgs e)
        {

        }

        private void toolStripButton1_Click(object sender, EventArgs e)
        {
            backend.Logout();
        }

        private void button3_Click(object sender, EventArgs e)
        {
            listBox1.Items.Clear();
            ZeitBuchung[] buchungen = backend.GetRecentZeitBuchungenForEmployee();
            foreach (ZeitBuchung b in buchungen)
            {
                listBox1.Items.Add(b.ToString());
            }
        }

        public void OnLoginStatusChanged(StatusCode status)
        {
            switch (status)
            {
                case StatusCode.LOGIN_SUCCESSFULL:
                    this.toolStripLabel2.Text = backend.GetFullUsername();
                break;
            }
        }

        private void button4_Click(object sender, EventArgs e)
        {
            
            backend.DBX();
            //textBox1.Text = (backend.QueryTest1());
        }

        private void button1_Click(object sender, EventArgs e)
        {
            backend.NewZeitBuchungForNow(ZeitBuchung.ZBTyp.KOMMEN);
            button3_Click(null, null);
        }

        private void button2_Click(object sender, EventArgs e)
        {
            backend.NewZeitBuchungForNow(ZeitBuchung.ZBTyp.GEHEN);
            button3_Click(null, null);
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
    }
}