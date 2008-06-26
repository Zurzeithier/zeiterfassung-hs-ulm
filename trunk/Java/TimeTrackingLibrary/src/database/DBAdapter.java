package database;

import beans.TimeBookingBean;
import beans.TimeBookingTableEntryBean;
import beans.UserBean;
import exceptions.DBException;
import java.util.List;

/**
 * Interface of database adapters
 * @author manuel, steffen
 */
public interface DBAdapter {

    /**
     * Modifies user data
     * @param user
     * @return Whether the operation was successful
     * @throws exceptions.DBException
     */
    boolean changeUser(UserBean user) throws DBException;

    /**
     * Returns a list of time bookings by ID
     * @param mid
     * @param number Number of rows that should be returned
     * @return List for table entry
     * @throws exceptions.DBException
     */
    List<TimeBookingTableEntryBean> getTimeBookings(int mid, int number) throws DBException;
    
    /**
     * Returns the last time booking
     * @param mid
     * @return TimeBookingBean
     * @throws exceptions.DBException
     */
    TimeBookingBean getLastBooking(int mid) throws DBException;
    
    /**
     * Adds one time booking to the database
     * @param bean
     * @return Whether the operation was successful
     * @throws exceptions.DBException
     */
    boolean addTimeBooking(TimeBookingBean bean) throws DBException;

    /**
     * Returns one user by username
     * @param username
     * @return UserBean
     * @throws exceptions.DBException
     */
    UserBean getUser(String username) throws DBException;

    /**
     * Returns one user by mid
     * @param mid
     * @return UserBean
     * @throws exceptions.DBException
     */
    UserBean getUser(int mid) throws DBException;

    /**
     * Inserts one user
     * @param user
     * @return Whether the operation was successful
     * @throws exceptions.DBException
     */
    boolean insertUser(UserBean user) throws DBException;

}
