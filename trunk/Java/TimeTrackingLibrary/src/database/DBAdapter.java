/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package database;

import beans.TimeBookingBean;
import beans.TimeBookingTableEntryBean;
import beans.UserBean;
import exceptions.DBException;
import java.util.List;

/**
 * Interface of database adapters
 * @author manuel
 */
public interface DBAdapter {

    boolean changeUser(UserBean user) throws DBException;

    /**
     * 
     * @param mid
     * @param number Number of rows that should be returned
     * @return
     * @throws exceptions.DBException
     */
    List<TimeBookingTableEntryBean> getTimeBookings(int mid, int number) throws DBException;
    
    TimeBookingBean getLastBooking(int mid) throws DBException;
    
    boolean addTimeBooking(TimeBookingBean bean) throws DBException;

    UserBean getUser(String username) throws DBException;

    UserBean getUser(int mid) throws DBException;

    boolean insertUser(UserBean user) throws DBException;

}
