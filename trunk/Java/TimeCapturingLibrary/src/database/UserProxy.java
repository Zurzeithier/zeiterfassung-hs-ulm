/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package database;

import beans.UserBean;
import exceptions.DBException;

/**
 *
 * @author manuel
 */
public interface UserProxy {

    boolean changeUser(UserBean user) throws DBException;

    boolean insertUser(UserBean user) throws DBException;
    
    UserBean getUser(String username) throws DBException;

    UserBean getUser(int mid) throws DBException;

}
