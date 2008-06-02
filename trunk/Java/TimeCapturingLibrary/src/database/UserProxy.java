/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package database;

import beans.UserBean;
import errors.DBError;

/**
 *
 * @author manuel
 */
public interface UserProxy {

    boolean changeUser(UserBean user);

    boolean insertUser(UserBean user);
    
    UserBean getUser(String username) throws DBError;

    UserBean getUser(int mid);

}
