using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Text;
using System.Windows.Forms;

namespace Zeiterfassung.NET
{
    public partial class LoginForm : Form
    {
        public Action<int> delExitApp;
        public Backend backend;
        public LoginForm()
        {
            InitializeComponent();
        }

        public void LoadOptionsFromFile(string fn)
        {
            
            string line = null;
            string[] line_split;
            try
            {
                System.IO.StreamReader file = new System.IO.StreamReader(fn);
                while ((line = file.ReadLine()) != null)
                {
                    textBox1.AppendText(line+Environment.NewLine);
                }
                file.Close();
            }
            catch (Exception e)
            { }
            
        }

        private void Form1_Load(object sender, EventArgs e)
        {
            LoadOptionsFromFile("settings.txt");
        }
        private void ApplyOptions()
        {
            backend.SetDefaultOptions();
            string[] line_split;
            foreach( String line in textBox1.Lines)
            {
                if (line.Contains("="))
                {
                    line_split = line.Split("=".ToCharArray());
                    backend.SetOption(line_split[0], line_split[1]);
                }


            }
        }


        private void btnConnect_Click(object sender, EventArgs e)
        {
            btnLogin.Enabled = false;
            System.Threading.Thread.Sleep(1000);
            ApplyOptions();
            backend.Login(this.textBox2.Text, this.textBox3.Text);
            btnLogin.Enabled = true;
        }

        private void Form1_FormClosed(object sender, FormClosedEventArgs e)
        {
            delExitApp(0);
        }

        public void LoginStatusChanged(StatusCode status)
        {
            switch (status)
            {
                case StatusCode.LOGIN_SUCCESSFULL:
                    Hide();
                    break;
                case StatusCode.LOGOUT_SUCCESSFULL:
                    Show();
                    break;
                case StatusCode.DB_CONNECT_FAILED:
                    lblStatus.Text = "Database Connection Failed";
                    break;
            }
        }

        private void button3_Click(object sender, EventArgs e)
        {
            delExitApp(0);
        }




    }
}