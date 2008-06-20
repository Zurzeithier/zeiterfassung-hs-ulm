/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package database;

import beans.TPTypeBean;
import beans.TimeAccountBean;
import beans.TimeBookingBean;
import beans.TimeBookingTableEntryBean;
import beans.UserBean;
import exceptions.DBException;
import java.util.List;

/**
 *
 * @author manuel
 */
public interface DBAdapter {

    boolean changeUser(UserBean user) throws DBException;

    TimeAccountBean getTimeAccount();

    List<TimeBookingTableEntryBean> getTimeBookings(int mid) throws DBException;
    
    boolean addTimeBooking(TimeBookingBean bean) throws DBException;

    TPTypeBean getType(int typId) throws DBException;

    UserBean getUser(String username) throws DBException;

    UserBean getUser(int mid) throws DBException;

    boolean insertUser(UserBean user) throws DBException;

}
