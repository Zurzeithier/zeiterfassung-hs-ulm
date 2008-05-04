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
            textBox1.Text = (backend.QueryTest1());
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
    }
}