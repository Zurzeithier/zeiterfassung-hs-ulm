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
        public LibZES.Backend backend;

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
            LibZES.ZeitBuchung[] buchungen = backend.QueryRecentZeitBuchungenForEmployee();
            foreach (LibZES.ZeitBuchung b in buchungen)
            {
                listBox1.Items.Add(b.ToString());
            }
        }

        private void button4_Click(object sender, EventArgs e)
        {
            backend.DBX();
            textBox1.Text = (backend.QueryTest1());
        }
    }
}