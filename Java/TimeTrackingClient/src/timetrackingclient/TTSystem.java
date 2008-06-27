package timetrackingclient;

import application.Application;
import java.text.SimpleDateFormat;
import java.util.List;
import javax.swing.table.DefaultTableModel;

/**
 *
 * @author  manuel, steffen
 */
public class TTSystem extends javax.swing.JDialog
{

    private DefaultTableModel bookingTableModel = new DefaultTableModel();

    /** Creates new form TTSystem
     * @param parent
     * @param modal 
     */
    public TTSystem(java.awt.Frame parent, boolean modal)
    {
        super(parent, modal);
        initComponents();
        reset();
    }

    private void reset()
    {
        statusMessage.setText(null);
        usernameTextField.setText(Application.getInstance().getUser().getUsername());
        
        bookingTableModel.addColumn("Vorname");
        bookingTableModel.addColumn("Nachname");
        bookingTableModel.addColumn("Kommen Buchung");
        bookingTableModel.addColumn("Gehen Buchung");
        
        updateBookingTable();
    }

    private void updateBookingTable()
    {
        try
        {
            // Call Web Service Operation for Go-Booking
            time_tracking.BookingHandlerServiceService serviceBooking = new time_tracking.BookingHandlerServiceService();
            time_tracking.BookingHandlerService portBooking = serviceBooking.getBookingHandlerServicePort();
            List<time_tracking.TimeBookingTableEntryBean> bookingList = portBooking.getBookings(Application.getInstance().getUser().getMid(), 10);

            SimpleDateFormat dateFormatter = new SimpleDateFormat("dd.MM.yyyy 'um' HH:mm 'Uhr'");
            
            bookingTableModel.setRowCount(0);
            for (time_tracking.TimeBookingTableEntryBean entry : bookingList)
            {
                Object[] rowData = new Object[4];
                rowData[0] = entry.getFirstname();
                rowData[1] = entry.getLastname();
                rowData[2] = dateFormatter.format(entry.getComeBooking().toGregorianCalendar().getTime());
                rowData[3] = dateFormatter.format(entry.getGoBooking().toGregorianCalendar().getTime());
                bookingTableModel.addRow(rowData);
            }

        }
        catch (Exception ex)
        {
            System.err.println(ex.toString());
        }

    }

    /** This method is called from within the constructor to
     * initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is
     * always regenerated by the Form Editor.
     */
    @SuppressWarnings("unchecked")
    // <editor-fold defaultstate="collapsed" desc="Generated Code">//GEN-BEGIN:initComponents
    private void initComponents() {

        logoutButton = new javax.swing.JButton();
        jTabbedPane1 = new javax.swing.JTabbedPane();
        jPanel2 = new javax.swing.JPanel();
        comeButton = new javax.swing.JButton();
        goButton = new javax.swing.JButton();
        jScrollPane1 = new javax.swing.JScrollPane();
        bookingsTable = new javax.swing.JTable();
        jPanel3 = new javax.swing.JPanel();
        jLabel1 = new javax.swing.JLabel();
        jLabel2 = new javax.swing.JLabel();
        usernameTextField = new javax.swing.JTextField();
        passwordTextField = new javax.swing.JPasswordField();
        changeUserButton = new javax.swing.JButton();
        statusMessage = new javax.swing.JLabel();

        setDefaultCloseOperation(javax.swing.WindowConstants.DISPOSE_ON_CLOSE);
        setName("Form"); // NOI18N
        setResizable(false);

        org.jdesktop.application.ResourceMap resourceMap = org.jdesktop.application.Application.getInstance(timetrackingclient.TimeTrackingClientApp.class).getContext().getResourceMap(TTSystem.class);
        logoutButton.setText(resourceMap.getString("logoutButton.text")); // NOI18N
        logoutButton.setName("logoutButton"); // NOI18N
        logoutButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                logoutButtonActionPerformed(evt);
            }
        });

        jTabbedPane1.setName("jTabbedPane1"); // NOI18N

        jPanel2.setName("jPanel2"); // NOI18N

        comeButton.setText(resourceMap.getString("comeButton.text")); // NOI18N
        comeButton.setName("comeButton"); // NOI18N
        comeButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                comeButtonActionPerformed(evt);
            }
        });

        goButton.setText(resourceMap.getString("goButton.text")); // NOI18N
        goButton.setName("goButton"); // NOI18N
        goButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                goButtonActionPerformed(evt);
            }
        });

        jScrollPane1.setName("jScrollPane1"); // NOI18N

        bookingsTable.setModel(bookingTableModel);
        bookingsTable.setName("bookingsTable"); // NOI18N
        jScrollPane1.setViewportView(bookingsTable);

        javax.swing.GroupLayout jPanel2Layout = new javax.swing.GroupLayout(jPanel2);
        jPanel2.setLayout(jPanel2Layout);
        jPanel2Layout.setHorizontalGroup(
            jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel2Layout.createSequentialGroup()
                .addContainerGap()
                .addGroup(jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addComponent(jScrollPane1, javax.swing.GroupLayout.Alignment.TRAILING, javax.swing.GroupLayout.DEFAULT_SIZE, 662, Short.MAX_VALUE)
                    .addGroup(jPanel2Layout.createSequentialGroup()
                        .addComponent(comeButton, javax.swing.GroupLayout.PREFERRED_SIZE, 181, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED, 301, Short.MAX_VALUE)
                        .addComponent(goButton, javax.swing.GroupLayout.PREFERRED_SIZE, 180, javax.swing.GroupLayout.PREFERRED_SIZE)))
                .addContainerGap())
        );
        jPanel2Layout.setVerticalGroup(
            jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel2Layout.createSequentialGroup()
                .addContainerGap()
                .addGroup(jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(comeButton)
                    .addComponent(goButton))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                .addComponent(jScrollPane1, javax.swing.GroupLayout.PREFERRED_SIZE, 268, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
        );

        jTabbedPane1.addTab(resourceMap.getString("jPanel2.TabConstraints.tabTitle"), jPanel2); // NOI18N

        jPanel3.setName("jPanel3"); // NOI18N

        jLabel1.setText(resourceMap.getString("jLabel1.text")); // NOI18N
        jLabel1.setName("jLabel1"); // NOI18N

        jLabel2.setText(resourceMap.getString("jLabel2.text")); // NOI18N
        jLabel2.setName("jLabel2"); // NOI18N

        usernameTextField.setName("usernameTextField"); // NOI18N

        passwordTextField.setName("passwordTextField"); // NOI18N
        passwordTextField.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                passwordTextFieldActionPerformed(evt);
            }
        });

        changeUserButton.setText(resourceMap.getString("changeUserButton.text")); // NOI18N
        changeUserButton.setName("changeUserButton"); // NOI18N
        changeUserButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                changeUserButtonActionPerformed(evt);
            }
        });

        statusMessage.setName("statusMessage"); // NOI18N

        javax.swing.GroupLayout jPanel3Layout = new javax.swing.GroupLayout(jPanel3);
        jPanel3.setLayout(jPanel3Layout);
        jPanel3Layout.setHorizontalGroup(
            jPanel3Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel3Layout.createSequentialGroup()
                .addContainerGap()
                .addGroup(jPanel3Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(jPanel3Layout.createSequentialGroup()
                        .addGroup(jPanel3Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                            .addComponent(jLabel1)
                            .addComponent(jLabel2))
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addGroup(jPanel3Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING, false)
                            .addComponent(usernameTextField)
                            .addComponent(passwordTextField)
                            .addComponent(changeUserButton, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)))
                    .addComponent(statusMessage))
                .addContainerGap(391, Short.MAX_VALUE))
        );
        jPanel3Layout.setVerticalGroup(
            jPanel3Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel3Layout.createSequentialGroup()
                .addGap(14, 14, 14)
                .addComponent(statusMessage)
                .addGap(18, 18, 18)
                .addGroup(jPanel3Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(jLabel1)
                    .addComponent(usernameTextField, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addGroup(jPanel3Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(jLabel2)
                    .addComponent(passwordTextField, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                .addGap(18, 18, 18)
                .addComponent(changeUserButton)
                .addContainerGap(194, Short.MAX_VALUE))
        );

        jTabbedPane1.addTab(resourceMap.getString("jPanel3.TabConstraints.tabTitle"), jPanel3); // NOI18N

        javax.swing.GroupLayout layout = new javax.swing.GroupLayout(getContentPane());
        getContentPane().setLayout(layout);
        layout.setHorizontalGroup(
            layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(layout.createSequentialGroup()
                .addContainerGap()
                .addGroup(layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addComponent(jTabbedPane1, javax.swing.GroupLayout.DEFAULT_SIZE, 698, Short.MAX_VALUE)
                    .addComponent(logoutButton, javax.swing.GroupLayout.PREFERRED_SIZE, 127, javax.swing.GroupLayout.PREFERRED_SIZE))
                .addContainerGap())
        );
        layout.setVerticalGroup(
            layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(layout.createSequentialGroup()
                .addContainerGap()
                .addComponent(logoutButton)
                .addGap(18, 18, 18)
                .addComponent(jTabbedPane1, javax.swing.GroupLayout.DEFAULT_SIZE, 376, Short.MAX_VALUE))
        );

        pack();
    }// </editor-fold>//GEN-END:initComponents

private void logoutButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_logoutButtonActionPerformed
    Application.getInstance().setUser(null);
    this.setVisible(false);
}//GEN-LAST:event_logoutButtonActionPerformed

private void passwordTextFieldActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_passwordTextFieldActionPerformed
}//GEN-LAST:event_passwordTextFieldActionPerformed

private void changeUserButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_changeUserButtonActionPerformed
    try
    {
        time_tracking.UserBean userbean = Application.getInstance().getUser();

        userbean.setUsername(usernameTextField.getText());
        String pwd = new String(passwordTextField.getPassword());
        if (pwd.length() > 0)
        {
            userbean.setPassword(pwd);
        }

        // Call Web Service Operation
        time_tracking.UserHandlerServiceService service = new time_tracking.UserHandlerServiceService();
        time_tracking.UserHandlerService port = service.getUserHandlerServicePort();

        boolean result = port.changeUser(userbean.getMid(), userbean);
        if (result)
        {
            statusMessage.setText("Änderung war erfolgreich!");
        }
        else
        {
            statusMessage.setText("Änderung fehlgeschlagen");
        }

    }
    catch (Exception ex)
    {
        System.err.println(ex.toString());
    }
}//GEN-LAST:event_changeUserButtonActionPerformed

private void comeButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_comeButtonActionPerformed
try
    {
        // Call Web Service Operation for Go-Booking
        time_tracking.BookingHandlerServiceService serviceBooking = new time_tracking.BookingHandlerServiceService();
        time_tracking.BookingHandlerService portBooking = serviceBooking.getBookingHandlerServicePort();
        boolean resultBooking = portBooking.makeComeBooking(Application.getInstance().getUser().getMid());

        updateBookingTable();
    }
    catch (Exception ex)
    {
        System.err.println(ex.toString());
    }
}//GEN-LAST:event_comeButtonActionPerformed

private void goButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_goButtonActionPerformed
try
    {
        // Call Web Service Operation for Go-Booking
        time_tracking.BookingHandlerServiceService serviceBooking = new time_tracking.BookingHandlerServiceService();
        time_tracking.BookingHandlerService portBooking = serviceBooking.getBookingHandlerServicePort();
        boolean resultBooking = portBooking.makeGoBooking(Application.getInstance().getUser().getMid());

        updateBookingTable();
    }
    catch (Exception ex)
    {
        System.err.println(ex.toString());
    }
}//GEN-LAST:event_goButtonActionPerformed

    /**
     * @param args the command line arguments
     */
    public static void main(String args[])
    {
        java.awt.EventQueue.invokeLater(new 
        

              Runnable()
            {

                    public void run()
            {
                TTSystem dialog = new TTSystem(new javax.swing.JFrame(), true);
                dialog.addWindowListener(new java.awt
                

                      .event.WindowAdapter 
                    
                        ()
                {

                    @Override
                    public void windowClosing(java.awt.event.WindowEvent e)
                    {
                        System.exit(0);
                    }

                });
                dialog.setVisible(true);
            }

        });
    }

    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JTable bookingsTable;
    private javax.swing.JButton changeUserButton;
    private javax.swing.JButton comeButton;
    private javax.swing.JButton goButton;
    private javax.swing.JLabel jLabel1;
    private javax.swing.JLabel jLabel2;
    private javax.swing.JPanel jPanel2;
    private javax.swing.JPanel jPanel3;
    private javax.swing.JScrollPane jScrollPane1;
    private javax.swing.JTabbedPane jTabbedPane1;
    private javax.swing.JButton logoutButton;
    private javax.swing.JPasswordField passwordTextField;
    private javax.swing.JLabel statusMessage;
    private javax.swing.JTextField usernameTextField;
    // End of variables declaration//GEN-END:variables
}
