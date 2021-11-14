import numpy as np
import json
import sys
import pandas as pd
import matplotlib.pyplot as plt

cell_df=pd.read_excel('dataset.xlsx')                    #read data from excel file
#for checking whether data is read correctly
first_five_rows = cell_df.head()                         # contains first five cols of the data
no_of_rows_columns = cell_df.shape
dataset_size = cell_df.size
fire_details=cell_df['fire'].value_counts()              # the no of fire and no_fire/exlposion

# distribution of classes 
no_fire_df = cell_df[cell_df['fire']==2][0:50]           #to check the data 
fire_df = cell_df[cell_df['fire']==4][0:10]

no_fire_df.plot(kind='scatter' , x='methane' , y='dust' , color='blue', label='nofire')    # to visualize the data only
fire_df.plot(kind='scatter' , x='methane' , y='dust' , color='red', label='fire')


#removal of unwanted columns
columns_data_type = cell_df.dtypes    # give us the data types of the columns we can remove unwanted columns eg whose data type is not int or float
# devide into train and test
spliter=cell_df.columns               # to seperate features( attributes ) and Target class

# remove the fire class column and make feature dataframe
feature_df = cell_df[['methane', 'smoke', 'co', 'temperature', 'humidity', 'dust', 'hydrogen']]

# making numpy array from feature dataframe
# X is independant variable means  
X=np.asarray(feature_df)         
#Y is dependent variable
y=np.asarray(cell_df['fire'])
X[0:5]
y[0:5]


                                  # train test split

                                  
from sklearn.model_selection import train_test_split
X_train , X_test , y_train , y_test  = train_test_split(X,y , test_size=0.2, random_state=4)      # will return 4 numpy arrays in that order

#print(X_train.shape)  #print the no of rows and columns of the training features)
#print(y_train.shape)  #print the no of rows and columns of the training class fire)
#print(X_test.shape)  #print the no of rows and columns of the test features)
#print(y_test.shape)  #print the no of rows and columns of the test class fire)

                                  #modeling svm 

from sklearn import svm
 
classifier = svm.SVC (kernel='linear' , gamma='auto' , C=2)  # c is the penalty for wrongly ploted class
classifier.fit (X_train,y_train)


                               #evaluation from svm
import mysql.connector
# connecting to database
try:
    mydb=mysql.connector.connect(
    host="localhost",       #host name 
    user="root",            #user name
    passwd="",              #password leave empty if no password
    database="minedata"    #database name from which data is to be fetched   
    )
    # print('database connetion successfull')
    mycursor =mydb.cursor()                                                 #to execute sql queries we make an object 
    mycursor.execute("SELECT methan,smoke,carbonmono,temperature,humidity,dust,hydrogen FROM data1 order by id desc limit 5")   # select first five records from the database table
    myresult =mycursor.fetchall()                                           # brig  all the data in lastly excuted statement in this case we will get  methan smoke etc.
    testing_values=myresult
    predicted_values=classifier.predict(testing_values)                     # will return 1Dimensional array which will be prediction    
    # now we will loop through array and make final prediction  
    i=0
    for x in predicted_values:
        if x==2:
            i+=1
    if i>=3:
        ms1=[1,"there is no chance of explosion "]
        print(json.dumps(ms1))
        #exite()
    else:
        ms2=[2,"there can be a potential explosion"]
        print(json.dumps(ms2))
        #exite()
    mydb.close()
except:
    ms3=[3,"error getting input data for prediction from the database"]
    print (json.dumps(ms3))
    exite()