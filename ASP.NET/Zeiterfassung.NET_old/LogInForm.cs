using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace Zeiterfassung.NET
{
    public partial class LogIn : Form
    {
        public string[] args = new string[4];

        // Delegate
        public delegate void LogInClickedHandler(string[] args);

        // Event
        public event LogInClickedHandler LogInClickedHandlerEvent;
        
        //protected void OnLogInClickedHandler(string[] args);

        public LogIn()
        {
            InitializeComponent();
        }

        private void btn_login_Click(object sender, EventArgs e)
        {
            args[0] = tb_server.Text;
            args[1] = tb_user.Text;
            args[2] = tb_pass.Text;
            args[3] = tb_database.Text;

            // Check if there are any Subscribers
            if (LogInClickedHandlerEvent != null)
            {
                // Call the Event
                LogInClickedHandlerEvent(args);
            }

        }
    }
}
